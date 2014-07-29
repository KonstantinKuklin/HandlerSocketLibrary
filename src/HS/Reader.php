<?php

namespace HS;

use HS\Exceptions\WrongParameterException;
use HS\Requests\AuthRequest;
use HS\Requests\OpenIndexRequest;
use HS\Requests\SelectRequest;
use PHP_Timer;
use Stream\Stream;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class Reader implements ReaderInterface
{
    /** @var int */
    private $countQueries = 0;

    /** @var double */
    private $timeQueries = 0;

    /** @var Stream */
    private $stream = null;

    /** @var int */
    private $currentIndexIterator = 1;

    /** @var array */
    private $indexList = array();

    /** @var array */
    private $requestQueue = array();

    /** @var array */
    private $keysList = array();

    /** @var Driver */
    private $driver = null;

    /** @var null|string */
    private $authKey = null;

    /**
     * @param string $url
     * @param int    $port
     * @param string $authKey
     */
    public function __construct($url, $port, $authKey = null)
    {
        $this->driver = new Driver();
        $this->stream = new Stream($url, Stream::PROTOCOL_TCP, $port, $this->driver);
        $this->authKey = $authKey;

        // if auth set then try to auth
        if ($this->authKey !== null) {
            $this->authenticate($this->authKey);
        }
    }

    /**
     * @param string $authKey
     *
     * @throws WrongParameterException
     * @return AuthRequest
     */
    public function authenticate($authKey)
    {
        if (!is_string($authKey) || is_string($authKey) && strlen($authKey) < 1) {
            throw new WrongParameterException(
                sprintf(
                    "Authenticate command require string password value, but got %s with value %s.",
                    gettype($authKey),
                    $authKey
                )
            );
        }
        $authRequest = new AuthRequest(trim($authKey));
        $this->addRequestToQueue($authRequest);
        $this->incrementCountQuery();

        return $authRequest;
    }

    /**
     * Opening index
     *
     * The 'open_index' request has the following syntax.
     *
     * P <indexId> <dbName> <tableName> <indexName> <columns> [<fcolumns>]
     *
     * Once an 'open_index' request is issued, the HandlerSocket plugin opens the
     * specified index and keep it open until the client connection is closed. Each
     * open index is identified by <indexId>. If <indexId> is already open, the old
     * open index is closed. You can open the same combination of <dbName>
     * <tableName> <indexName> multiple times, possibly with different <columns>.
     *
     * For efficiency, keep <indexId> small as far as possible.
     *
     * @param int    $indexId
     *               Is a number in decimal.
     * @param string $dbName
     * @param string $tableName
     * @param string $indexName
     *               To open the primary key, use PRIMARY as $indexName.
     * @param array  $columns
     *               Is a array of column names.
     *
     * @return OpenIndexRequest
     */
    public function openIndex($indexId, $dbName, $tableName, $indexName, $columns)
    {
        $indexRequest = new OpenIndexRequest($indexId, $dbName, $tableName, $indexName, $columns);
        $this->addRequestToQueue($indexRequest);
        $this->setKeysToIndexId($indexId, $columns);
        $this->incrementCountQuery();

        return $indexRequest;
    }

    /**
     * @param string $dbName
     * @param string $tableName
     * @param string $indexName
     * @param array  $columns
     *
     * @return int
     */
    public function getIndexId($dbName, $tableName, $indexName, $columns)
    {
        $columnsToSearch = $columns;
        if (is_array($columns)) {
            $columnsToSearch = implode('', $columnsToSearch);
        } else {
            $columnsToSearch = '';
        }

        $indexMapValue = $dbName . $tableName . $indexName . $columnsToSearch;
        if (!$indexId = $this->getIndexIdFromArray($indexMapValue)) {
            $indexId = $this->getCurrentIterator();
            $this->openIndex($indexId, $dbName, $tableName, $indexName, $columns);
            $this->addIndexIdToArray($indexMapValue, $indexId);
        }

        return $indexId;
    }

    /**
     * Getting data
     *
     * The 'find' request has the following syntax:
     *
     *      <indexId> <comparisonOperation> <keysCount> <key1> ... <keyN> [LIM]
     *
     * LIM is optional sequence of the following parameters:
     *
     *      <limit> <offset>
     *
     *
     * @param int    $indexId
     *               Is a number. This number must be an <indexId> specified by a
     *               'open_index' request executed previously on the same connection.
     * @param string $comparisonOperation
     *               Specifies the comparison operation to use. The current version of
     *               HandlerSocket supports '=', '>', '>=', '<', and '<='.
     * @param array  $keys
     *               Specify the index column values to fetch.
     * @param int    $limit
     * @param int    $offset
     *
     * @return SelectRequest
     */
    public function select($indexId, $comparisonOperation, $keys, $offset = 0, $limit = 0)
    {
        $selectRequest = new SelectRequest(
            $indexId,
            $comparisonOperation,
            $keys,
            $offset,
            $limit,
            $this->getKeysByIndexId($indexId)
        );

        $this->addRequestToQueue($selectRequest);
        $this->incrementCountQuery();

        return $selectRequest;
    }

    /**
     * @return array
     * @throws \Stream\Exceptions\StreamException
     */
    public function getResponses()
    {
        PHP_Timer::start();
        if (!$this->isRequestQueueEmpty()) {
            $this->sendRequests();
        }

        $responsesList = array();
        foreach ($this->requestQueue as &$request) {
            /** @var $request RequestInterface */
            $response = $this->getStream()->getContentsByStreamGetLine(1024, Driver::EOL);
            $request->setResponseData($response);
            $responseObject = $request->getResponse();
            $responsesList[] = $responseObject;
        }
        $this->requestQueue = array();
        $this->addTimeQueries(PHP_Timer::stop());

        return $responsesList;
    }

    /**
     * @return int
     */
    public function getCountRequestsInQueue()
    {
        return count($this->requestQueue);
    }

    /**
     * @return int
     */
    public function getCountQueries()
    {
        return $this->countQueries;
    }

    /**
     * @return double
     */
    public function getTimeQueries()
    {
        return $this->timeQueries;
    }

    /**
     * @param RequestInterface $request
     */
    protected function addRequestToQueue($request)
    {
        $this->requestQueue[] = $request;
    }

    /**
     * @return boolean
     */
    protected function isRequestQueueEmpty()
    {
        return empty($this->requestQueue);
    }

    /**
     * @throws \Stream\Exceptions\StreamException
     */
    protected function sendRequests()
    {
        foreach ($this->requestQueue as $request) {
            /** @var $request RequestInterface */
            $this->getStream()->sendContents($request->getRequestParameters());
        }
    }

    /**
     * @param int   $indexId
     * @param array $keys
     */
    protected function setKeysToIndexId($indexId, $keys)
    {
        $this->keysList[$indexId] = $keys;
    }

    /**
     * @param int $indexId
     *
     * @return array
     */
    protected function getKeysByIndexId($indexId)
    {
        return $this->keysList[$indexId];
    }

    /**
     * @return void
     */
    protected function incrementCountQuery()
    {
        $this->countQueries++;
    }

    /**
     * @param double $time
     */
    protected function addTimeQueries($time)
    {
        $this->timeQueries += $time;
    }

    /**
     * @param boolean $increment
     *
     * @return int
     */
    private function getCurrentIterator($increment = true)
    {
        if ($increment) {
            return $this->currentIndexIterator++;
        }

        return $this->currentIndexIterator;
    }

    /**
     * @param string $index
     *
     * @return boolean
     */
    private function getIndexIdFromArray($index)
    {
        if (isset($this->indexList[$index])) {
            return $this->indexList[$index];
        }

        return false;
    }

    /**
     * @param string $indexMapValue
     * @param int    $indexId
     */
    private function addIndexIdToArray($indexMapValue, $indexId)
    {
        $this->indexList[$indexMapValue] = $indexId;
    }

    /**
     * @return Stream
     */
    private function getStream()
    {
        return $this->stream;
    }
} 