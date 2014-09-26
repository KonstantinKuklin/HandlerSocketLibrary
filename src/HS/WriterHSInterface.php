<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS;

use HS\Query\DecrementQuery;
use HS\Query\DeleteQuery;
use HS\Query\IncrementQuery;
use HS\Query\InsertQuery;
use HS\Query\UpdateQuery;

interface WriterHSInterface extends ReaderInterface
{
    const COMMAND_UPDATE = 'U';
    const COMMAND_DELETE = 'D';
    const COMMAND_INCREMENT = '+';
    const COMMAND_DECREMENT = '-';

    /**
     * @param array  $columns
     * @param string $dbName
     * @param string $tableName
     * @param string $indexName
     * @param string $comparisonOperation
     * @param array  $keys
     * @param array  $values
     * @param bool   $suffix
     * @param null   $offset
     * @param null   $limit
     *
     * @return mixed
     */
    public function update(
        array $columns, $dbName, $tableName, $indexName, $comparisonOperation, $keys, $values, $suffix = false,
        $offset = null, $limit = null
    );

    /**
     * @param int    $indexId
     * @param string $comparisonOperation
     * @param array  $keys
     * @param array  $values
     * @param bool   $suffix
     * @param int    $limit
     * @param int    $offset
     *
     * @return UpdateQuery
     */
    public function updateByIndex(
        $indexId, $comparisonOperation, array $keys, array $values, $suffix = false, $limit = 1, $offset = 0
    );

    /**
     * @param array  $columns
     * @param string $dbName
     * @param string $tableName
     * @param string $indexName
     * @param string $comparisonOperation
     * @param array  $keys
     * @param bool   $suffix
     * @param int    $offset
     * @param int    $limit
     *
     * @return mixed
     */
    public function delete(
        array $columns, $dbName, $tableName, $indexName, $comparisonOperation, array $keys, $suffix = false,
        $offset = 0,
        $limit = 1
    );

    /**
     * @param int    $indexId
     * @param string $comparisonOperation
     * @param array  $keys
     * @param bool   $suffix
     * @param int    $offset
     * @param int    $limit
     *
     * @return DeleteQuery
     */
    public function deleteByIndex(
        $indexId, $comparisonOperation, array $keys, $suffix = false, $offset = 0, $limit = 1
    );

    /**
     * @param array  $columns
     * @param string $dbName
     * @param string $tableName
     * @param string $indexName
     * @param string $comparisonOperation
     * @param array  $keys
     * @param array  $valueList
     * @param bool   $suffix
     * @param int    $offset
     * @param int    $limit
     *
     * @return mixed
     */
    public function increment(
        array $columns, $dbName, $tableName, $indexName, $comparisonOperation, array $keys, array $valueList,
        $suffix = false, $offset = 0,
        $limit = 1
    );

    /**
     * @param int    $indexId
     * @param string $comparisonOperation
     * @param array  $keys
     * @param array  $valueList
     * @param bool   $suffix
     * @param int    $limit
     * @param int    $offset
     *
     * @return IncrementQuery
     */
    public function incrementByIndex(
        $indexId, $comparisonOperation, array $keys, array $valueList, $suffix = false, $limit = 1, $offset = 0
    );

    /**
     * @param int    $indexId
     * @param string $comparisonOperation
     * @param array  $keys
     * @param array  $valueList
     * @param bool   $suffix
     * @param int    $limit
     * @param int    $offset
     *
     * @return DecrementQuery
     */
    public function decrementByIndex(
        $indexId, $comparisonOperation, array $keys, array $valueList, $suffix = false, $limit = 1, $offset = 0
    );

    /**
     * @param array  $columnList
     * @param string $dbName
     * @param string $tableName
     * @param string $indexName
     * @param string $comparisonOperation
     * @param array  $keys
     * @param array  $valueList
     * @param bool   $suffix
     * @param int    $offset
     * @param int    $limit
     *
     * @return DecrementQuery
     */
    public function decrement(
        array $columnList, $dbName, $tableName, $indexName, $comparisonOperation, array $keys, array $valueList,
        $suffix = false,
        $offset = 0,
        $limit = 1
    );

    /**
     * @param int   $indexId
     * @param array $valueList
     *
     * @return InsertQuery
     */
    public function insertByIndex($indexId, array $valueList);

    /**
     * @param array  $columnList
     * @param string $dbName
     * @param string $tableName
     * @param string $indexName
     * @param array  $valueList
     *
     * @return InsertQuery
     */
    public function insert(array $columnList, $dbName, $tableName, $indexName, array $valueList);
}