<?php

namespace HS;

use HS\Builder\QueryBuilderInterface;
use HS\Exception\Exception;
use HS\Exception\InvalidArgumentException;
use HS\Query\OpenIndexQuery;
use HS\Query\QueryInterface;
use Stream\Connection;
use Stream\Exception\ReadStreamException;
use Stream\ReceiveMethod\StreamGetLineMethod;
use Stream\Stream;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
abstract class CommonClient
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

    /** @var null|string */
    private $authKey = null;

    /** @var Stopwatch */
    private $stopWatch;

    /** @var int */
    private $lengthToRead = 1024;

    public $debugResultList = array();

    /**
     * @param string $authKey
     *
     * @return \HS\Query\AuthQuery
     */
    abstract public function authenticate($authKey);

    /**
     * @param string $dbName
     * @param string $tableName
     * @param string $indexName
     * @param array  $columnList
     * @param bool   $returnOnlyId
     * @param array  $filterColumnList
     *
     * @return int|OpenIndexQuery
     */
    abstract public function getIndexId(
        $dbName, $tableName, $indexName, array $columnList, $returnOnlyId = true, array $filterColumnList = array()
    );

    /**
     * @param string $url
     * @param int    $port
     * @param string $authKey
     * @param bool   $debug
     * @param int    $lengthToRead
     *
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function __construct($url, $port, $authKey = null, $debug = false, $lengthToRead = 1024)
    {
        if ($debug) {
            $this->stopWatch = new Stopwatch();
        }

        $this->lengthToRead = (int)$lengthToRead;
        if ($this->lengthToRead < 1) {
            throw new Exception('Read chunk size can`t be < 1.');
        }

        $this->authKey = $authKey;
        $this->stream = new Stream($url, Connection::PROTOCOL_TCP, $port);
        $this->stream->setReceiveMethod(new StreamGetLineMethod($lengthToRead, Driver::EOL));
        $this->open();
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        $isDebug = ($this->stopWatch !== null);

        return $isDebug;
    }

    /**
     * @return \HS\Result\ResultAbstract[]
     * @throws Exception
     * @throws \Stream\Exception\StreamException
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
                $this->stopWatch->start('send_read_data');
                $this->sendQuery($query);
            }
            $this->stream->isReadyForReading();
            try {

                $content = '';
                do {
                    $contentChunk = $this->stream->getContents();
                    $content .= $contentChunk;
                    // content + 1 ( for "\n" symbol )
                    $contentSize = strlen($contentChunk) + 1;
                } while ($contentSize >= $this->lengthToRead);

                $query->setResultData($content);

                $resultObject = $query->getResult();

                // if debug mode enabled
                if ($this->isDebug()) {
                    // get query time in seconds.milliseconds
                    $currentQueryTime = $this->stopWatch->stop('send_read_data')->getDuration() / 1000;

                    // add info of spent time for this Query
                    $resultObject->setTime($currentQueryTime);
                    $this->addTimeQueries($currentQueryTime);
                    $this->debugResultList[] = $resultObject;
                }
                $resultsList[] = $resultObject;
                // add time to general time counter
            } catch (ReadStreamException $e) {
                throw new Exception("Read stream error. Can't read from stream. URL: " . $this->getUrlConnection());
            }
        }

        $this->queryListNotSent = array();

        return $resultsList;
    }

    /**
     * @return int
     */
    public function getCountQueriesInQueue()
    {
        return count($this->queryListNotSent);
    }

    /**
     * @return int
     */
    public function getCountQueries()
    {
        return $this->countQueries;
    }

    /**
     * @return float
     */
    public function getTimeQueries()
    {
        return $this->timeQueries;
    }

    /**
     * @return string
     */
    public function getUrlConnection()
    {
        return $this->stream->getConnection()->getUrlConnection();
    }

    /**
     * @param QueryBuilderInterface $queryBuilder
     *
     * @throws Exception
     * @return \HS\Query\QueryAbstract
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
            $queryForAddList = $queryBuilder->getQuery($openIndexQuery, $this);
        } else {
            $queryForAddList = $queryBuilder->getQuery($openIndexQuery->getIndexId(), $this, $openIndexQuery);
        }

        if (count($queryForAddList) === 0) {
            throw new Exception("Returned empty list queries.");
        }

        $queryForAdd = null;
        foreach ($queryForAddList as $queryForAdd) {
            $this->addQuery($queryForAdd);
        }

        return $queryForAdd;
    }

    /**
     * @param QueryInterface $query
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

    public function reOpen()
    {
        $this->close();
        $this->open();
    }

    /**
     * @throws \Stream\Exception\StreamException
     */
    public function close()
    {
        $this->currentIndexIterator = 0;
        $this->countQueries = 0;
        $this->queryListNotSent = array();
        $this->indexList = array();
        $this->keysList = array();
        $this->stream->close();
    }

    /**
     * @throws Exception
     * @throws \Stream\Exception\ConnectionStreamException
     * @throws \Stream\Exception\StreamException
     */
    public function open()
    {
        if ($this->stream->isOpened()) {
            throw new Exception("Stream already opened.");
        }
        $this->stream->open();
        $this->stream->setBlockingOff();
        $this->stream->setReadTimeOut(0, (float)500000);
        $this->authenticateOnInit();
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
        if ($this->stream->sendContents($query->getQueryString() . Driver::EOL) > 0) {
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
     * @throws InvalidArgumentException
     * @return array
     */
    protected function getKeysByIndexId($indexId)
    {
        if (!array_key_exists($indexId, $this->keysList)) {
            throw new InvalidArgumentException(sprintf("Don't find any Index with this indexId:%d.", $indexId));
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
    protected function getCurrentIterator($increment = true)
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
    protected function getIndexIdFromArray($index)
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
    protected function addIndexIdToArray($indexMapValue, $indexId)
    {
        $this->indexList[$indexMapValue] = $indexId;
    }

    private function authenticateOnInit()
    {
        if ($this->authKey !== null) {
            $this->authenticate($this->authKey);
        }
    }
}
