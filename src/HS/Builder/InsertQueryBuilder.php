<?php

namespace HS\Builder;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class InsertQueryBuilder extends QueryBuilderAbstract
{

    public function __construct()
    {
    }

    /**
     * @param array $row
     *
     * @return InsertQueryBuilder
     */
    public function addRow(array $row)
    {
        if (empty($this->columnList)) {
            $this->columnList = array_keys($row);
        }
        $this->valueList[] = array_values($row);

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
        $this->dbName = $dbName;

        return $this;
    }

    /**
     * @param $tableName
     *
     * @return InsertQueryBuilder
     */
    public function toTable($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * @param $indexName
     *
     * @return InsertQueryBuilder
     */
    public function toIndex($indexName)
    {
        $this->indexName = $indexName;

        return $this;
    }

    /**
     * @return string
     */
    public function getQueryClassPath()
    {
        return 'HS\Query\InsertQuery';
    }

    /**
     * @param int $limit
     *
     * @return InsertQueryBuilder
     */
    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param int $offset
     *
     * @return InsertQueryBuilder
     */
    public function offset($offset)
    {
        $this->offset = $offset;

        return $this;
    }
}