<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;

use HS\Query\DecrementQuery;
use HS\Query\DeleteQuery;
use HS\Query\IncrementQuery;
use HS\Query\InsertQuery;
use HS\Query\OpenIndexQuery;
use HS\Query\UpdateQuery;

class Writer extends Reader implements WriterHSInterface
{
    public function update(
        $columns, $dbName, $tableName, $indexName, $comparisonOperation, $keys, $values, $offset = null, $limit = null
    ) {
        $indexId = $this->getIndexId($dbName, $tableName, $indexName, $columns, false);
        $openIndexQuery = null;
        if ($indexId instanceof OpenIndexQuery) {
            $openIndexQuery = $indexId;
            $indexId = $openIndexQuery->getIndexId();
        }

        $query = new UpdateQuery(
            $indexId,
            $comparisonOperation,
            $keys,
            $offset,
            $limit,
            $openIndexQuery,
            $values
        );

        $this->addQuery($query);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function updateByIndex($indexId, $comparisonOperation, $keys, $values, $limit = null, $offset = null)
    {
        $updateQuery = $this->modifyByIndexQuery(
            'Update',
            $indexId,
            $comparisonOperation,
            $keys,
            $values,
            $offset,
            $limit
        );

        return $updateQuery;
    }

    public function delete(
        $columns, $dbName, $tableName, $indexName, $comparisonOperation, $keys, $offset = null, $limit = null
    ) {
        $indexId = $this->getIndexId($dbName, $tableName, $indexName, $columns, false);
        $openIndexQuery = null;
        if ($indexId instanceof OpenIndexQuery) {
            $openIndexQuery = $indexId;
            $indexId = $openIndexQuery->getIndexId();
        }

        $query = new DeleteQuery(
            $indexId,
            $comparisonOperation,
            $keys,
            $offset,
            $limit,
            $openIndexQuery
        );
        $this->addQuery($query);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteByIndex($indexId, $comparisonOperation, $keys, $limit = null, $offset = null)
    {
        $deleteQuery = $this->modifyByIndexQuery(
            'Delete',
            $indexId,
            $comparisonOperation,
            $keys,
            null,
            $offset,
            $limit
        );

        return $deleteQuery;
    }

    public function increment(
        $columns, $dbName, $tableName, $indexName, $comparisonOperation, $keys, $values, $offset = null, $limit = null
    ) {
        $indexId = $this->getIndexId($dbName, $tableName, $indexName, $columns, false);
        $openIndexQuery = null;
        if ($indexId instanceof OpenIndexQuery) {
            $openIndexQuery = $indexId;
            $indexId = $openIndexQuery->getIndexId();
        }

        $query = new IncrementQuery(
            $indexId,
            $comparisonOperation,
            $keys,
            $offset,
            $limit,
            $openIndexQuery,
            $values
        );
        $this->addQuery($query);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function incrementByIndex($indexId, $comparisonOperation, $keys, $values, $limit = null, $offset = null)
    {
        $incrementQuery = $this->modifyByIndexQuery(
            'Increment',
            $indexId,
            $comparisonOperation,
            $keys,
            $values,
            $offset,
            $limit
        );

        return $incrementQuery;
    }


    public function decrement(
        $columns, $dbName, $tableName, $indexName, $comparisonOperation, $keys, $values, $offset = null, $limit = null
    ) {
        $indexId = $this->getIndexId($dbName, $tableName, $indexName, $columns, false);
        $openIndexQuery = null;
        if ($indexId instanceof OpenIndexQuery) {
            $openIndexQuery = $indexId;
            $indexId = $openIndexQuery->getIndexId();
        }

        $query = new DecrementQuery(
            $indexId,
            $comparisonOperation,
            $keys,
            $offset,
            $limit,
            $openIndexQuery,
            $values
        );
        $this->addQuery($query);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function decrementByIndex($indexId, $comparisonOperation, $keys, $values, $limit = null, $offset = null)
    {
        $decrementQuery = $this->modifyByIndexQuery(
            'Decrement',
            $indexId,
            $comparisonOperation,
            $keys,
            $values,
            $offset,
            $limit
        );

        return $decrementQuery;
    }

    public function insert(
        $columns, $dbName, $tableName, $indexName, $values
    ) {
        $indexId = $this->getIndexId($dbName, $tableName, $indexName, $columns, false);
        $openIndexQuery = null;
        if ($indexId instanceof OpenIndexQuery) {
            $openIndexQuery = $indexId;
            $indexId = $openIndexQuery->getIndexId();
        }

        $query = new InsertQuery(
            $indexId,
            $values,
            $openIndexQuery
        );
        $this->addQuery($query);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function insertByIndex($indexId, $values)
    {
        $updateQuery = new InsertQuery(
            $indexId,
            $values
        );

        $this->addQuery($updateQuery);

        return $updateQuery;
    }

    /**
     * @param string     $queryClassName
     * @param int        $indexId
     * @param string     $comparisonOperation
     * @param array      $keys
     * @param array|null $values
     * @param int        $offset
     * @param int        $limit
     *
     * @return null|QueryInterface
     */
    private function modifyByIndexQuery(
        $queryClassName, $indexId, $comparisonOperation, $keys, $values, $offset = null, $limit = null
    ) {
        $className = 'HS\Query\\' . $queryClassName . 'Query';
        $modifyQuery = null;
        if ($queryClassName === 'Delete') {
            $modifyQuery = new $className(
                $indexId,
                $comparisonOperation,
                $keys,
                $offset,
                $limit
            );
        } else {
            $modifyQuery = new $className(
                $indexId,
                $comparisonOperation,
                $keys,
                $offset,
                $limit,
                null,
                $values
            );
        }

        $this->addQuery($modifyQuery);

        return $modifyQuery;
    }
}
