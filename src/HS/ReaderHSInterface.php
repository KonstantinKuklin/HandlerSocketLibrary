<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS;

use HS\Query\SelectQuery;
use HS\Query\OpenIndexQuery;
use HS\Exception\InvalidArgumentException;
use HS\Query\AuthQuery;


/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
interface ReaderHSInterface
{
    /**
     * @param string $authKey
     *
     * @throws InvalidArgumentException
     * @return AuthQuery
     */
    public function authenticate($authKey);

    /**
     * Opening index
     *
     * The 'open_index' Query has the following syntax.
     *
     * P <indexId> <dbName> <tableName> <indexName> <columns> [<fcolumns>]
     *
     * Once an 'open_index' Query is issued, the HandlerSocket plugin opens the
     * specified index and keep it open until the client connection is closed. Each
     * open index is identified by <indexId>. If <indexId> is already open, the old
     * open index is closed. You can open the same combination of <dbName>
     * <tableName> <indexName> multiple times, possibly with different <columns>.
     *
     * For efficiency, keep <indexId> small as far as possible.
     *
     * @param int    $indexId
     *               Is a number in decimal.
     * @param string $dbName
     * @param string $tableName
     * @param string $indexName
     *               To open the primary key, use PRIMARY as $indexName.
     * @param array  $columnList
     *               Is a array of column names.
     *
     * @param array  $filterColumnList
     *
     * @return OpenIndexQuery
     */
    public function openIndex($indexId, $dbName, $tableName, $indexName, array $columnList, array $filterColumnList = array());

    /**
     * @param string $dbName
     * @param string $tableName
     * @param string $indexName
     * @param array  $columnList
     *
     * @param bool   $returnOnlyId
     *
     * @param array  $filterColumnList
     *
     * @return int|OpenIndexQuery
     */
    public function getIndexId(
        $dbName, $tableName, $indexName, array $columnList, $returnOnlyId = true, array $filterColumnList = array()
    );

    /**
     * Getting data
     *
     * The 'find' Query has the following syntax:
     *
     *      <indexId> <comparisonOperation> <keysCount> <key1> ... <keyN> [LIM]
     *
     * LIM is optional sequence of the following parameters:
     *
     *      <limit> <offset>
     *
     *
     * @param int    $indexId
     *               Is a number. This number must be an <indexId> specified by a
     *               'open_index' Query executed previously on the same connection.
     * @param string $comparisonOperation
     *               Specifies the comparison operation to use. The current version of
     *               HandlerSocket supports '=', '>', '>=', '<', and '<='.
     * @param array  $keys
     *               Specify the index column values to fetch.
     * @param int    $limit
     * @param int    $offset
     *
     * @return SelectQuery
     */
    public function selectByIndex($indexId, $comparisonOperation, array $keys, $offset = 0, $limit = 0);
}