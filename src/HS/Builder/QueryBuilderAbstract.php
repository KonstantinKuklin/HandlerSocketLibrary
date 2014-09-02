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

    protected $limit = null;
    protected $offset = null;

    protected $constructArray = array();
    protected $whereComparison = HSInterface::EQUAL;
    protected $whereValues = array();
    protected $filter = array();

    protected $in = array();

    /**
     * @param      $indexId
     * @param null $openIndexQuery
     *
     * @return QueryInterface
     */
    abstract public function getQuery($indexId, $openIndexQuery = null);

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return $this->constructArray;
    }

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

    public function fromIndex($index)
    {
        $this->index = $index;

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

    public function where($comparison, array $list)
    {
        $this->filter = array();
        $this->whereValues = array();
        $this->whereComparison = $comparison;
        $this->in = array();

        // check is ordered list of keys
        for ($i = 0, $countWhere = count($list); $i < $countWhere; $i++) {
            $key = $this->constructArray[$i];
            if (!isset($list[$key])) {
                throw new \Exception("The key`s must be set with out skip on select( key1, key2). Where(key2,key1)");
            }
            $this->whereValues[] = $list[$key];
        }

        return $this;
    }

    public function whereIn($key, array $values)
    {
        $this->whereValues = array(1); // TODO check

        if (false === $index = array_search($key, $this->constructArray)) {
            throw new \Exception("Can't find key in columns list.");
        }
        $this->in = array(
            'icol' => $index,
            'ivlen' => count($values),
            'iv' => $values
        );

        return $this;
    }

    public function andWhere($key, $comparison, $value, $type = HSInterface::FILTER_TYPE_SKIP)
    {
        if (false !== array_search($key, $this->constructArray)) {
            throw new \Exception("AndWhere used only with non selected columns.");
        }

        $this->filter[] = array(
            'key' => $key,
            'comparison' => $comparison,
            'value' => $value,
            'type' => $type
        );

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

    public function getFilterColumns()
    {
        $fcolumns = array();
        foreach ($this->filter as $filter) {
            $fcolumns[] = $filter['key'];
        }

        return $fcolumns;
    }

    public function isValid()
    {
        //TODO
    }
} 