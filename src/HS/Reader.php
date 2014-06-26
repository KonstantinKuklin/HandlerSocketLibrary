<?php

namespace HS;

use HS\Requests\AuthRequest;
use HS\Requests\OpenIndexRequest;
use HS\Requests\SelectRequest;
use SplQueue;
use Stream\Stream;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class Reader implements ReaderInterface
{
    /**
     * @var null|\Stream\Stream
     */
    private $stream = null;
    /**
     * @var int
     */
    private $currentIndexIterator = 1;
    /**
     * @var array
     */
    private $indexList = array();


    private $requestQueue = array();
    /**
     * @var array
     */
    private $keysList = array();

//    private $keysQueue = null;

    /**
     * @var Driver
     */
    private $driver = null;

    private $authKey = null;

    /**
     * @param $url
     * @param $port
     * @param $authKey
     */
    public function __construct($url, $port, $authKey = null)
    {
        $this->driver = new Driver();
        $this->stream = new Stream($url, Stream::PROTOCOL_TCP, $port, $this->driver);
        //$this->requestQueue = new SplQueue();
        $this->keysQueue = new SplQueue();
        $this->authKey = $authKey;

        // if auth set then try to auth
        if ($this->authKey !== null) {
            $this->authenticate($this->authKey);
        }
    }

    /**
     * @param string $authKey
     *
     * @return \HS\Requests\AuthRequest
     */
    public function authenticate($authKey)
    {
        $authRequest = new AuthRequest($authKey);
        $this->addRequestToQueue($authRequest);

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
     * @param array  $fColumns
     *               Is a array of column names.This parameter is optional.
     *
     * @return OpenIndexRequest
     */
    public function openIndex($indexId, $dbName, $tableName, $indexName, $columns, $fColumns = array())
    {
        $indexRequest = new OpenIndexRequest($indexId, $dbName, $tableName, $indexName, $columns, $fColumns);
        $this->addRequestToQueue($indexRequest);
        $this->setKeysToIndexId($indexId, $columns);

        return $indexRequest;
    }

    // пытаемся определить открыта ли уже эта БД и таблица на чтение, если нет - открываем
    /**
     * @param       $dbName
     * @param       $tableName
     * @param       $indexName
     * @param       $columns
     * @param array $fcolumns
     *
     * @return bool|int
     */
    public function getIndexId($dbName, $tableName, $indexName, $columns, $fcolumns = array())
    {
        $columnsToSearch = $columns;
        if (is_array($columns)) {
//            sort($columnsToSearch);
            $columnsToSearch = implode('', $columnsToSearch);
        } else {
            $columnsToSearch = '';
        }

        $indexMapValue = $dbName . $tableName . $indexName . $columnsToSearch;
        if (!$indexId = $this->getIndexIdFromArray($indexMapValue)) {
            $indexId = $this->getCurrentIterator();
            $this->openIndex($indexId, $dbName, $tableName, $indexName, $columns, $fcolumns);
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
     * @param $indexId             int
     *                             Is a number. This number must be an <indexId> specified by a
     *                             'open_index' request executed previously on the same connection.
     * @param $comparisonOperation string
     *                             Specifies the comparison operation to use. The current version of
     *                             HandlerSocket supports '=', '>', '>=', '<', and '<='.
     * @param $keys                array
     *                             Specify the index column values to fetch.
     * @param $limit               int
     * @param $offset              int
     *                             are numbers. When omitted, it works
     *                             as if 1 and 0 are specified. These parameter works like LIMIT of SQL.
     *                             These values don't include the number of records skipped by a filter.
     *
     * @return SelectRequest
     */
    public function select(
        $indexId, $comparisonOperation, $keys, $limit = 0, $offset = 0
    ) {
        $selectRequest = new SelectRequest(
            $indexId,
            $comparisonOperation,
            $keys,
            $offset,
            $limit,
            $this->getKeysByIndexId($indexId)
        );

        $this->addRequestToQueue($selectRequest);

        return $selectRequest;
    }

    /**
     * @param RequestInterface $request
     */
    protected function addRequestToQueue($request)
    {
        $this->requestQueue[] = $request;
    }

//    /**
//     * @return RequestInterface
//     */
//    protected function getRequestFromQueue()
//    {
//        return array_shift($this->requestQueue);
//    }

    /**
     * @return bool
     */
    protected function isRequestQueueEmpty()
    {
        return empty($this->requestQueue);
    }

    /**
     * @return array
     * @throws \Stream\Exceptions\StreamException
     */
    public function getResponses()
    {
        if (!$this->isRequestQueueEmpty()) {
            $this->sendRequests();
        }

        $responsesList = array();
        foreach ($this->requestQueue as &$request) {
            /** @var $request RequestInterface */
            $response = $this->getStream()->getContents(1024, Driver::EOL);
            $request->setResponseData($response);
            $responseObject = $request->getResponse();
            $responsesList[] = $responseObject;
        }
        $this->requestQueue = array();

        return $responsesList;
    }

    // Отсылаем все команды, которые находятся в очереди
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
     * @param bool $increment
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
     * @param $index
     *
     * @return bool
     */
    private function getIndexIdFromArray($index)
    {
        if (isset($this->indexList[$index])) {
            return $this->indexList[$index];
        }

        return false;
    }

//    protected function addKeysToQueue($keys)
//    {
//        $this->keysQueue->push($keys);
//    }
//
//    protected function getKeysFromQueue()
//    {
//        return $this->keysQueue->shift();
//    }

    /**
     * @param $indexMapValue
     * @param $indexId
     */
    private function addIndexIdToArray($indexMapValue, $indexId)
    {
        $this->indexList[$indexMapValue] = $indexId;
    }

    /**
     * @param int   $indexId
     * @param array $keys
     */
    protected function setKeysToIndexId($indexId, $keys)
    {
        $this->keysList[$indexId] = $keys;
    }

    protected function getKeysByIndexId($indexId)
    {
        return $this->keysList[$indexId];
    }

    /**
     * @return Stream
     */
    private function getStream()
    {
        return $this->stream;
    }
} 