<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

use HS\Exception\InvalidArgumentException;
use HS\ReaderInterface;

abstract class QueryAbstract implements QueryInterface
{
    static protected $queryResultMap = array(
        'HS\Query\AuthQuery' => 'HS\Result\AuthResult',
        'HS\Query\TextQuery' => 'HS\Result\TextResult',
        'HS\Query\OpenIndexQuery' => 'HS\Result\OpenIndexResult',
        'HS\Query\SelectQuery' => 'HS\Result\SelectResult',
        'HS\Query\UpdateQuery' => 'HS\Result\UpdateResult',
        'HS\Query\InsertQuery' => 'HS\Result\InsertResult',
        'HS\Query\DeleteQuery' => 'HS\Result\DeleteResult',
        'HS\Query\IncrementQuery' => 'HS\Result\IncrementResult',
        'HS\Query\DecrementQuery' => 'HS\Result\DecrementResult',
    );

    protected $resultObject = null;
    protected $socket = null;
    protected $indexId = null;
    protected $openIndexQuery = null;
    protected $valueList = array();
    protected $queryClassName = null;

    public function __construct()
    {
        $this->queryClassName = get_called_class();
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->resultObject;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getQueryString();

    /**
     * {@inheritdoc}
     */
    public function getIndexId()
    {
        return $this->indexId;
    }

    /**
     * {@inheritdoc}
     */
    public function setResultData($data)
    {
        $this->setResultObject(self::$queryResultMap[$this->getQueryClassName()], $data);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $this->getSocket()->addQuery($this);
        $this->getSocket()->getResultList();

        return $this;
    }

    /**
     * @return string
     */
    protected function getQueryClassName()
    {
        return $this->queryClassName;
    }

    /**
     * @throws InvalidArgumentException
     * @return ReaderInterface
     */
    protected function getSocket()
    {
        if (!($this->socket instanceof ReaderInterface)) {
            throw new InvalidArgumentException('Socket not found');
        }

        return $this->socket;
    }

    /**
     * @param string $className
     * @param mixed  $data
     */
    protected function setResultObject($className, $data)
    {
        $this->resultObject = new $className($this, $data, $this->openIndexQuery);
    }
} 