<?php
namespace HS\Builder;

use HS\Component\Comparison;
use HS\Component\Filter;
use HS\Component\InList;
use HS\Exception\InvalidArgumentException;
use HS\Query\SelectQuery;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
abstract class FindQueryBuilderAbstract extends QueryBuilderAbstract
{
    /**
     * @param array $columnList
     */
    public function __construct(array $columnList)
    {
        $this->columnList = $columnList;
    }

    /**
     * @param string $databaseName
     *
     * @return $this
     */
    public function fromDataBase($databaseName)
    {
        $this->dbName = $databaseName;

        return $this;
    }

    /**
     * @param string $tableName
     *
     * @return $this
     */
    public function fromTable($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * @param string $indexName
     *
     * @return $this
     */
    public function fromIndex($indexName)
    {
        $this->indexName = $indexName;

        return $this;
    }

    /**
     * @return array
     */
    public function getColumnList()
    {
        return $this->columnList;
    }

    /**
     * @param string $comparison
     * @param array  $list
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function where($comparison, array $list)
    {
        $this->comparison = $comparison;
        $columnList = $this->getColumnList();

        // check is ordered list of keys
        for ($i = 0, $countWhere = count($list); $i < $countWhere; $i++) {
            $key = $columnList[$i];
            if (!isset($list[$key])) {
                throw new InvalidArgumentException(
                    "The key`s must be set with out skip on select( key1, key2). Where(key2,key1)"
                );
            }
            $this->keyList[] = $list[$key];
        }

        return $this;
    }

    /**
     * @param string $key
     * @param array  $values
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function whereIn($key, array $values)
    {
        $columnList = $this->getColumnList();
        $this->comparison = Comparison::EQUAL;
        $this->keyList = array(1);

        if (false === $index = array_search($key, $columnList)) {
            throw new InvalidArgumentException("Can't find key in columns list.");
        }
        $inList = new InList($index, $values);
        $this->inKeyList = $inList;

        return $this;
    }

    /**
     * @param string $columnName
     * @param string $comparison
     * @param string $key
     * @param string $type
     *
     * @return $this
     */
    public function andWhere($columnName, $comparison, $key, $type = Filter::FILTER_TYPE_SKIP)
    {
        $position = array_search($columnName, $this->filterColumnList);
        if ($position === false) {
            $this->filterColumnList[] = $columnName;
            $position = count($this->filterColumnList) - 1;
        }
        $filter = new Filter($comparison, $position, $key, $type);
        $this->filterList[] = $filter;

        return $this;
    }

    /**
     * @return Filter[]
     */
    public function getFilterList()
    {
        return $this->filterList;
    }

    /**
     * @return array
     */
    public function getFilterColumnList()
    {
        return $this->filterColumnList;
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int $offset
     *
     * @return $this
     */
    public function offset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return $this
     */
    public function withSuffix()
    {
        $this->suffix = true;

        return $this;
    }
} 