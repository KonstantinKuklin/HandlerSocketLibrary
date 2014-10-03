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
    /**
     * {@inheritdoc}
     */
    public function update(
        array $columns, $dbName, $tableName, $indexName, $comparisonOperation, $keys, $values, $suffix = false,
        $offset = null, $limit = null
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
            $this,
            $this->getKeysByIndexId($indexId),
            $offset,
            $limit,
            $openIndexQuery,
            null,
            array(),
            $suffix, $values
        );

        $this->addQuery($query);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function updateByIndex(
        $indexId, $comparisonOperation, array $keys, array $values, $suffix = false, $limit = null, $offset = null
    ) {
        $query = new UpdateQuery(
            $indexId,
            $comparisonOperation,
            $keys,
            $this,
            $this->getKeysByIndexId($indexId),
            $offset,
            $limit,
            null,
            null,
            array(),
            $suffix, $values
        );

        $this->addQuery($query);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        array $columns, $dbName, $tableName, $indexName, $comparisonOperation, array $keys, $suffix = false,
        $offset = null,
        $limit = null
    ) {
        $indexId = $this->getIndexId($dbName, $tableName, $indexName, $columns, false);
        $openIndexQuery = null;
        if ($indexId instanceof OpenIndexQuery) {
            $openIndexQuery = $indexId;
            $indexId = $openIndexQuery->getIndexId();
        }

        $deleteQuery = new DeleteQuery(
            $indexId,
            $comparisonOperation,
            $keys,
            $this,
            $this->getKeysByIndexId($indexId),
            $offset,
            $limit,
            $openIndexQuery,
            null,
            array(),
            $suffix
        );
        $this->addQuery($deleteQuery);

        return $deleteQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteByIndex($indexId, $comparisonOperation, array $keys, $suffix = false, $offset = 0, $limit = 1)
    {
        $deleteQuery = new DeleteQuery(
            $indexId,
            $comparisonOperation,
            $keys,
            $this,
            $this->getKeysByIndexId($indexId),
            $offset,
            $limit,
            null,
            null,
            array(),
            $suffix
        );
        $this->addQuery($deleteQuery);

        return $deleteQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function increment(
        array $columns, $dbName, $tableName, $indexName, $comparisonOperation, array $keys, array $valueList,
        $suffix = false, $offset = 0,
        $limit = 1
    ) {
        $indexId = $this->getIndexId($dbName, $tableName, $indexName, $columns, false);
        $openIndexQuery = null;
        if ($indexId instanceof OpenIndexQuery) {
            $openIndexQuery = $indexId;
            $indexId = $openIndexQuery->getIndexId();
        }

        $incrementQuery = new IncrementQuery(
            $indexId,
            $comparisonOperation,
            $keys,
            $this,
            $this->getKeysByIndexId($indexId),
            $offset,
            $limit,
            $openIndexQuery,
            null,
            array(),
            $suffix, $valueList
        );
        $this->addQuery($incrementQuery);

        return $incrementQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function incrementByIndex(
        $indexId, $comparisonOperation, array $keys, array $valueList, $suffix = false, $offset = 0, $limit = 1
    ) {
        $incrementQuery = new IncrementQuery(
            $indexId,
            $comparisonOperation,
            $keys,
            $this,
            $this->getKeysByIndexId($indexId),
            $offset,
            $limit,
            null,
            null,
            array(),
            $suffix, $valueList
        );
        $this->addQuery($incrementQuery);

        return $incrementQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function decrement(
        array $columnList, $dbName, $tableName, $indexName, $comparisonOperation, array $keys, array $valueList,
        $suffix = false, $offset = 0,
        $limit = 1
    ) {
        $indexId = $this->getIndexId($dbName, $tableName, $indexName, $columnList, false);
        $openIndexQuery = null;
        if ($indexId instanceof OpenIndexQuery) {
            $openIndexQuery = $indexId;
            $indexId = $openIndexQuery->getIndexId();
        }

        $decrementQuery = new DecrementQuery(
            $indexId,
            $comparisonOperation,
            $keys,
            $this,
            $this->getKeysByIndexId($indexId),
            $offset,
            $limit,
            $openIndexQuery,
            null,
            array(),
            $suffix, $valueList
        );
        $this->addQuery($decrementQuery);

        return $decrementQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function decrementByIndex(
        $indexId, $comparisonOperation, array $keys, array $valueList, $suffix = false, $offset = 0, $limit = 1
    ) {
        $decrementQuery = new DecrementQuery(
            $indexId,
            $comparisonOperation,
            $keys,
            $this,
            $this->getKeysByIndexId($indexId),
            $offset,
            $limit,
            null,
            null,
            array(),
            $suffix, $valueList
        );
        $this->addQuery($decrementQuery);

        return $decrementQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function insert(
        array $columnList, $dbName, $tableName, $indexName, array $valueList
    ) {
        $indexId = $this->getIndexId($dbName, $tableName, $indexName, $columnList, false);
        $openIndexQuery = null;
        if ($indexId instanceof OpenIndexQuery) {
            $openIndexQuery = $indexId;
            $indexId = $openIndexQuery->getIndexId();
        }

        $insertQuery = new InsertQuery($indexId, $valueList, $openIndexQuery);
        $this->addQuery($insertQuery);

        return $insertQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function insertByIndex($indexId, array $valueList)
    {
        $insertQuery = new InsertQuery($indexId, $valueList, null);
        $this->addQuery($insertQuery);

        return $insertQuery;
    }
}
