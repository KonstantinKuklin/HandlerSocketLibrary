<?php

namespace HS\Builder;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class InsertQueryBuilder extends QueryBuilderAbstract
{

    public function __construct()
    {
        parent::__construct(array());
    }

    /**
     * @param array $row
     *
     * @return InsertQueryBuilder
     */
    public function addRow(array $row)
    {
        if ($this->getParameter('columnList') === null) {
            $this->getParameterBag()->setParameter('columnList', array_keys($row));
        }
        $this->getParameterBag()->addRowToParameter('valueList', array_values($row));

        return $this;
    }

    /**
     * @param array $rowList
     *
     * @return InsertQueryBuilder
     */
    public function addRowList(array $rowList)
    {
        foreach ($rowList as $row) {
            $this->addRow($row);
        }

        return $this;
    }

    /**
     * @param $dbName
     *
     * @return InsertQueryBuilder
     */
    public function toDatabase($dbName)
    {
        $this->getParameterBag()->setParameter('dbName', $dbName);

        return $this;
    }

    /**
     * @param $tableName
     *
     * @return InsertQueryBuilder
     */
    public function toTable($tableName)
    {
        $this->getParameterBag()->setParameter('tableName', $tableName);

        return $this;
    }

    /**
     * @param $indexName
     *
     * @return InsertQueryBuilder
     */
    public function toIndex($indexName)
    {
        $this->getParameterBag()->setParameter('indexName', $indexName);

        return $this;
    }

    /**
     * @return string
     */
    public function getQueryClassPath()
    {
        return "HS\\Query\\InsertQuery";
    }

    /**
     * @param int $limit
     *
     * @return InsertQueryBuilder
     */
    public function limit($limit)
    {
        $this->getParameterBag()->setParameter('limit', $limit);

        return $this;
    }

    /**
     * @param int $offset
     *
     * @return InsertQueryBuilder
     */
    public function offset($offset)
    {
        $this->getParameterBag()->setParameter('offset', $offset);

        return $this;
    }
}