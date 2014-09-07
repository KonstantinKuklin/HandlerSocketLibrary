<?php
namespace HS\Builder;

use HS\Component\Comparison;
use HS\Component\Filter;
use HS\Component\InList;
use HS\Exception\WrongParameterException;

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
        parent::__construct(array('columnList' => $columnList));
    }

    /**
     * @param string $databaseName
     *
     * @return $this
     */
    public function fromDataBase($databaseName)
    {
        $this->getParameterBag()->setParameter('dbName', $databaseName);

        return $this;
    }

    /**
     * @param string $tableName
     *
     * @return $this
     */
    public function fromTable($tableName)
    {
        $this->getParameterBag()->setParameter('tableName', $tableName);

        return $this;
    }

    /**
     * @param string $indexName
     *
     * @return $this
     */
    public function fromIndex($indexName)
    {
        $this->getParameterBag()->setParameter('indexName', $indexName);

        return $this;
    }

    /**
     * @return array
     */
    public function getColumnList()
    {
        return $this->getParameter('columnList', array());
    }

    /**
     * @param string $comparison
     * @param array  $list
     *
     * @return $this
     * @throws WrongParameterException
     */
    public function where($comparison, array $list)
    {
        $this->getParameterBag()->setParameter('comparison', $comparison);
        $keyList = $this->getParameter('keyList', array());
        $columnList = $this->getColumnList();
        // check is ordered list of keys
        for ($i = 0, $countWhere = count($list); $i < $countWhere; $i++) {
            $key = $columnList[$i];
            if (!isset($list[$key])) {
                throw new WrongParameterException(
                    "The key`s must be set with out skip on select( key1, key2). Where(key2,key1)"
                );
            }
            $keyList[] = $list[$key];
        }
        $this->getParameterBag()->setParameter('keyList', $keyList);

        return $this;
    }

    /**
     * @param string $key
     * @param array  $values
     *
     * @return $this
     * @throws \Exception
     */
    public function whereIn($key, array $values)
    {
        $columnList = $this->getColumnList();
        $this->getParameterBag()->setParameter('comparison', Comparison::EQUAL);
        $this->getParameterBag()->setParameter('keyList', array(1));

        if (false === $index = array_search($key, $columnList)) {
            throw new WrongParameterException("Can't find key in columns list.");
        }
        $inList = new InList($index, $values);
        $this->getParameterBag()->setParameter('inKeyList', $inList);

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
        $filterColumnList = $this->getParameter('filterColumnList', array());
        $position = array_search($columnName, $filterColumnList);
        if ($position === false) {
            $filterColumnList[] = $columnName;
            $position = count($filterColumnList) - 1;
            $this->getParameterBag()->setParameter('filterColumnList', $filterColumnList);
        }
        $filter = new Filter($comparison, $position, $key, $type);

        $this->getParameterBag()->addRowToParameter('filterList', $filter);

        return $this;
    }

    /**
     * @return Filter[]
     */
    public function getFilterList()
    {
        return $this->getParameter('filterList', array());
    }

    /**
     * @return array
     */
    public function getFilterColumnList()
    {
        return $this->getParameter('filterColumnList', array());
    }

    /**
     * @param int $limit
     *
     * @return $this
     */
    public function limit($limit)
    {
        $this->getParameterBag()->setParameter('limit', $limit);

        return $this;
    }

    /**
     * @param int $offset
     *
     * @return $this
     */
    public function offset($offset)
    {
        $this->getParameterBag()->setParameter('offset', $offset);

        return $this;
    }
} 