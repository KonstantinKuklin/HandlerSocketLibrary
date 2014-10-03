<?php

namespace HS\Query;

use HS\Validator;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class OpenIndexQuery extends QueryAbstract
{
    private $dbName = '';
    private $tableName = '';
    private $indexName = '';
    private $columnList = array();
    private $filterColumnList = array();

    /**
     * @param int    $indexId
     * @param string $dbName
     * @param string $tableName
     * @param string $indexName
     * @param array  $columnList
     * @param        $socket
     * @param array  $filterColumnList
     */
    public function __construct(
        $indexId, $dbName, $tableName, $indexName, array $columnList, $socket, array $filterColumnList = array()
    ) {
        parent::__construct();
        Validator::validateIndexId($indexId);
        $this->indexId = $indexId;

        Validator::validateDbName($dbName);
        $this->dbName = $dbName;

        Validator::validateTableName($tableName);
        $this->tableName = $tableName;

        // add default index
        if (empty($indexName)) {
            $indexName = 'PRIMARY';
        }

        Validator::validateIndexName($indexName);
        $this->indexName = $indexName;

        Validator::validateColumnList($columnList);
        $this->columnList = $columnList;

        $this->socket = $socket;

        if (!empty($filterColumnList)) {
            Validator::validateColumnList($filterColumnList);
            $this->filterColumnList = $filterColumnList;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        return sprintf(
            "P\t%d\t%s\t%s\t%s\t%s\t%s",
            $this->indexId,
            $this->dbName,
            $this->tableName,
            $this->indexName,
            implode(',', $this->columnList),
            implode(',', $this->filterColumnList)
        );
    }
}