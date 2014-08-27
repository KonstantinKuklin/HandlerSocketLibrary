<?php
namespace HS\Builder;

use HS\HSInterface;
use HS\QueryInterface;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
abstract class QueryBuilderAbstract implements QueryBuilderInterface
{

    protected $db = null;
    protected $table = null;
    protected $index = 'PRIMARY';

    protected $limit = 0;
    protected $offset = 0;

    protected $constructArray = array();
    protected $where = array();

    protected $comparisonOperation = HSInterface::EQUAL;

    /**
     * @param      $indexId
     * @param null $openIndexQuery
     *
     * @return QueryInterface
     */
    abstract public function getQuery($indexId, $openIndexQuery = null);

    /**
     * @return array
     */
    abstract public function getColumns();

    public function __construct($columns)
    {
        $this->constructArray = $columns;
    }

    public function fromDataBase($db)
    {
        $this->db = $db;

        return $this;
    }

    public function fromTable($table)
    {
        $this->table = $table;

        return $this;
    }

    public function fromIndex($db)
    {
        $this->db = $db;

        return $this;
    }

    public function limit($limit)
    {
        $this->$limit = $limit;

        return $this;
    }

    public function offset($offset)
    {
        $this->$offset = $offset;

        return $this;
    }

    public function where($where)
    {
        if (is_array($where) || is_object($where)) {
            throw new \Exception("Where can't be array or object.");
        }
        $this->where = array();
        $this->where[] = $where;

        return $this;
    }

    public function andWhere($where)
    {
        $this->where[] = $where;

        return $this;
    }

    public function getDataBase()
    {
        return $this->db;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function setComparisonOperation($operation)
    {
        $this->comparisonOperation = $operation;

        return $this;
    }
} 