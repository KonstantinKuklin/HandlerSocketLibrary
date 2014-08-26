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
    /**
     * @param int    $indexId
     * @param string $comparisonOperation
     * @param array  $keys
     * @param array  $values
     * @param int    $limit
     * @param int    $offset
     *
     * @return UpdateQuery
     */
    public function updateByIndex($indexId, $comparisonOperation, $keys, $values, $limit = 1, $offset = 0);

    /**
     * @param int   $indexId
     * @param string $comparisonOperation
     * @param array  $keys
     * @param int    $limit
     * @param int    $offset
     *
     * @return DeleteQuery
     */
    public function deleteByIndex($indexId, $comparisonOperation, $keys, $limit = 1, $offset = 0);

    /**
     * @param int    $indexId
     * @param string $comparisonOperation
     * @param array  $keys
     * @param array  $values
     * @param int    $limit
     * @param int    $offset
     *
     * @return IncrementQuery
     */
    public function incrementByIndex($indexId, $comparisonOperation, $keys, $values, $limit = 1, $offset = 0);

    /**
     * @param int    $indexId
     * @param string $comparisonOperation
     * @param array  $keys
     * @param array  $values
     * @param int    $limit
     * @param int    $offset
     *
     * @return DecrementQuery
     */
    public function decrementByIndex($indexId, $comparisonOperation, $keys, $values, $limit = 1, $offset = 0);

    /**
     * @param $indexId
     * @param $values
     *
     * @return InsertQuery
     */
    public function insertByIndex($indexId, $values);
}