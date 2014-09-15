<?php

namespace HS;

use HS\Builder\QueryBuilderInterface;
use HS\Component\Comparison;
use HS\Component\Filter;
use HS\Component\InList;
use HS\Exception\WrongParameterException;
use HS\Query\AuthQuery;
use HS\Query\OpenIndexQuery;
use HS\Query\QueryInterface;
use HS\Query\SelectQuery;
use HS\Query\TextQuery;
use PHP_Timer;
use Stream\Connection;
use Stream\Exception\ReadStreamException;
use Stream\ReceiveMethod\StreamGetLineMethod;
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
    private $currentIndexIterator = 0;

    /** @var array */
    private $indexList = array();

    /** @var QueryInterface[] */
    private $queryListNotSent = array();

    /** @var array */
    private $keysList = array();

    /** @var Driver */
    private $driver = null;

    /** @var null|string */
    private $authKey = null;

    /** @var boolean */
    private $debug = false;

    public $debugResultList = array();

    /**
     * @param string  $url
     * @param int     $port
     * @param string  $authKey
     * @param boolean $debug
     */
    public function __construct($url, $port, $authKey = null, $debug = false)
    {
        if ($debug) {
            $this->debug = true;
        }

        $this->authKey = $authKey;
        $this->driver = new Driver();
        $this->stream = new Stream($url, Connection::PROTOCOL_TCP, $port, $this->driver);
        $this->stream->open();
        $this->stream->setBlockingOff();
        $this->stream->setReadTimeOut(0, (float)500000);
        $this->stream->setReceiveMethod(new StreamGetLineMethod(1024, Driver::EOL));

        // if auth set then try to auth
        if ($this->authKey !== null) {
            $this->authenticate($this->authKey);
        }
    }

    /**
     * {@inheritdoc}
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
        $authQuery = new AuthQuery(array('authKey' => trim($authKey)));
        $this->addQuery($authQuery);

        return $authQuery;
    }

    /**
     * @param string $queryText
     * @param string $queryClass
     *
     * @return TextQuery
     */
    public function text($queryText, $queryClass)
    {
        $textQuery = new TextQuery(array('text' => $queryText, 'socket' => $this), $queryClass);
        $this->addQuery($textQuery);

        return $textQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function openIndex(
        $indexId, $dbName, $tableName, $indexName, array $columnList, array $filterColumnList = array()
    ) {
        $indexQuery = new OpenIndexQuery(
            array(
                'indexId' => $indexId,
                'dbName' => $dbName,
                'tableName' => $tableName,
                'indexName' => $indexName,
                'columnList' => $columnList,
                'filterColumnList' => $filterColumnList,
                'socket' => $this,
            )
        );
        $this->addQuery($indexQuery);
        $this->setKeysToIndexId($indexId, $columnList);

        return $indexQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function getIndexId(
        $dbName, $tableName, $indexName, array $columnList, $returnOnlyId = true, array $filterColumnList = array()
    ) {
        $columnsToSearch = implode('', $columnList) . implode('', $filterColumnList);

        $indexMapValue = $dbName . $tableName . $indexName . $columnsToSearch;
        if (!$indexId = $this->getIndexIdFromArray($indexMapValue)) {
            $indexId = $this->getCurrentIterator();
            $openIndexQuery = $this->openIndex(
                $indexId,
                $dbName,
                $tableName,
                $indexName,
                $columnList,
                $filterColumnList
            );
            $this->addIndexIdToArray($indexMapValue, $indexId);

            // return OpenIndexQuery if we can
            if (!$returnOnlyId) {
                return $openIndexQuery;
            }
        }

        return $indexId;
    }

    /**
     * {@inheritdoc}
     */
    public function selectByIndex(
        $indexId, $comparisonOperation, array $keys, $offset = null, $limit = null, array $filterList = array()
    ) {
        $selectQuery = new SelectQuery(
            array(
                'indexId' => $indexId,
                'comparison' => $comparisonOperation,
                'keyList' => $keys,
                'offset' => $offset,
                'limit' => $limit,
                'columnList' => $this->getKeysByIndexId($indexId),
                'filterList' => $filterList,
                'socket' => $this,
            )
        );

        $this->addQuery($selectQuery);

        return $selectQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function selectInByIndex($indexId, $in, $offset = null, $limit = null, array $filterList = array())
    {
        if ($limit !== null && $limit < count($in)) {
            throw new WrongParameterException("Limit must be > count of in");
        }

        $selectQuery = new SelectQuery(
            array(
                'indexId' => $indexId,
                'comparison' => Comparison::EQUAL,
                'keyList' => array(1),
                'offset' => $offset,
                'limit' => ($limit !== null) ? $limit : count($in),
                'columnList' => $this->getKeysByIndexId($indexId),
                'inKeyList' => new InList(0, $in),
                'filterList' => $filterList,
                'socket' => $this,
            )
        );

        $this->addQuery($selectQuery);

        return $selectQuery;
    }

    /**
     * @param array    $columns
     * @param string   $dbName
     * @param string   $tableName
     * @param string   $indexName
     * @param string   $comparisonOperation
     * @param array    $keys
     * @param int      $offset
     * @param int      $limit
     * @param array    $filterColumnList
     * @param Filter[] $filterList
     *
     * @return SelectQuery
     */
    public function select(
        $columns, $dbName, $tableName, $indexName, $comparisonOperation, $keys, $offset = null, $limit = null,
        array $filterColumnList = array(), array $filterList = array()
    ) {
        $indexId = $this->getIndexId($dbName, $tableName, $indexName, $columns, false, $filterColumnList);
        $openIndexQuery = null;
        if ($indexId instanceof OpenIndexQuery) {
            $openIndexQuery = $indexId;
            $indexId = $openIndexQuery->getIndexId();
        }

        $selectQuery = new SelectQuery(
            array(
                'indexId' => $indexId,
                'comparison' => $comparisonOperation,
                'keyList' => $keys,
                'offset' => $offset,
                'limit' => $limit,
                'columnList' => $this->getKeysByIndexId($indexId),
                'openIndexQuery' => $openIndexQuery,
                'filterList' => $filterList,
                'socket' => $this,
            )
        );

        $this->addQuery($selectQuery);

        return $selectQuery;
    }

    /**
     * @param array  $columns
     * @param string $dbName
     * @param string $tableName
     * @param string $indexName
     * @param array  $in
     * @param int    $offset
     * @param int    $limit
     *
     * @throws WrongParameterException
     * @return SelectQuery
     */
    public function selectIn(
        $columns, $dbName, $tableName, $indexName, $in, $offset = null, $limit = null
    ) {
        $indexId = $this->getIndexId($dbName, $tableName, $indexName, $columns, false);
        $openIndexQuery = null;
        if ($indexId instanceof OpenIndexQuery) {
            $openIndexQuery = $indexId;
            $indexId = $openIndexQuery->getIndexId();
        }

        /** @var int $indexId */

        if ($limit !== null && $limit < count($in)) {
            throw new WrongParameterException("Limit must be > count of in");
        }

        $selectQuery = new SelectQuery(
            array(
                'indexId' => $indexId,
                'comparison' => Comparison::EQUAL,
                'keyList' => array(1),
                'offset' => $offset,
                'limit' => ($limit !== null) ? $limit : count($in),
                'columnList' => $this->getKeysByIndexId($indexId),
                'inKeyList' => new InList(0, $in),
                'socket' => $this,
            )
        );

        $this->addQuery($selectQuery);

        return $selectQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * {@inheritdoc}
     */
    public function getResultList()
    {
        $resultsList = array();

        // if debug mode enabled
        if (!$this->isDebug()) {
            $this->sendQueries();
        }

        foreach ($this->queryListNotSent as $query) {
            // if debug mode enabled
            if ($this->isDebug()) {
                // enable time counting
                PHP_Timer::start();
                $this->sendQuery($query);
            }
            $this->getStream()->isReadyForReading();
            try {
                $result = $this->getStream()->getContents();
                $query->setResultData($result);

                $ResultObject = $query->getResult();

                // if debug mode enabled
                if ($this->isDebug()) {
                    $currentQueryTime = PHP_Timer::stop();

                    // add info of spent time for this Query
                    $ResultObject->setTime($currentQueryTime);
                    $this->addTimeQueries($currentQueryTime);
                    $this->debugResultList[] = $ResultObject;
                }
                $resultsList[] = $ResultObject;
                // add time to general time counter
            } catch (ReadStreamException $e) {
                // TODO check
            }
        }

        $this->queryListNotSent = array();

        return $resultsList;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountQueriesInQueue()
    {
        return count($this->queryListNotSent);
    }

    /**
     * {@inheritdoc}
     */
    public function getCountQueries()
    {
        return $this->countQueries;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeQueries()
    {
        return $this->timeQueries;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrlConnection()
    {
        return $this->getStream()->getConnection()->getUrlConnection();
    }

    /**
     * {@inheritdoc}
     */
    public function addQueryBuilder(QueryBuilderInterface $queryBuilder)
    {
        $openIndexQuery = $this->getIndexId(
            $queryBuilder->getDataBase(),
            $queryBuilder->getTable(),
            $queryBuilder->getIndex(),
            $queryBuilder->getColumnList(),
            false,
            $queryBuilder->getFilterColumnList()
        );

        // if returned int
        if (is_int($openIndexQuery)) {
            /** @var int $openIndexQuery */
            $queryForAdd = $queryBuilder->getQuery($openIndexQuery);
        } else {
            /** @var OpenIndexQuery $openIndexQuery */
            $queryForAdd = $queryBuilder->getQuery($openIndexQuery->getIndexId(), $openIndexQuery);
        }
        $this->addQuery($queryForAdd);

        return $queryForAdd;
    }

    /**
     * {@inheritdoc}
     */
    public function addQuery(QueryInterface $query)
    {
        $this->queryListNotSent[] = $query;
    }

    /**
     * @throws \Stream\Exception\StreamException
     * @return void
     */
    public function sendQueries()
    {
        foreach ($this->queryListNotSent as $query) {
            $this->sendQuery($query);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function reOpen()
    {
        if ($this->getStream() !== null) {
            $this->getStream()->close();
        }

        $this->currentIndexIterator = 0;
        $this->queryListNotSent = array();
        $this->indexList = array();
        $this->keysList = array();
    }

    /**
     * @return boolean
     */
    protected function isQueryQueueEmpty()
    {
        return empty($this->queryListNotSent);
    }

    /**
     * @param QueryInterface $query
     *
     * @return boolean
     * @throws \Stream\Exception\NotStringStreamException
     * @throws \Stream\Exception\StreamException
     */
    protected function sendQuery(QueryInterface $query)
    {
        if ($this->getStream()->sendContents($query->getQueryParameters()) > 0) {
            // increment count of queries
            $this->countQueries++;

            return true;
        }

        return false;
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
     * @throws WrongParameterException
     * @return array
     */
    protected function getKeysByIndexId($indexId)
    {
        if (!array_key_exists($indexId, $this->keysList)) {
            throw new WrongParameterException(sprintf("Don't find any Index with this indexId:%d.", $indexId));
        }

        return $this->keysList[$indexId];
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
            $this->currentIndexIterator++;
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
