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
            array(
                'indexId' => $indexId,
                'comparison' => $comparisonOperation,
                'keyList' => $keys,
                'offset' => $offset,
                'limit' => $limit,
                'columnList' => $this->getKeysByIndexId($indexId),
                'valueList' => $values,
                'openIndexQuery' => $openIndexQuery,
                'socket' => $this,
            )
        );

        $this->addQuery($query);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function updateByIndex(
        $indexId, $comparisonOperation, array $keys, array $values, $limit = null, $offset = null
    ) {
        $updateQuery = new UpdateQuery(
            array(
                'indexId' => $indexId,
                'comparison' => $comparisonOperation,
                'keyList' => $keys,
                'offset' => $offset,
                'limit' => $limit,
                'columnList' => $this->getKeysByIndexId($indexId),
                'valueList' => $values,
                'socket' => $this,
            )
        );
        $this->addQuery($updateQuery);

        return $updateQuery;
    }

    public function delete(
        array $columns, $dbName, $tableName, $indexName, $comparisonOperation, array $keys, $offset = null,
        $limit = null
    ) {
        $indexId = $this->getIndexId($dbName, $tableName, $indexName, $columns, false);
        $openIndexQuery = null;
        if ($indexId instanceof OpenIndexQuery) {
            $openIndexQuery = $indexId;
            $indexId = $openIndexQuery->getIndexId();
        }

        $deleteQuery = new DeleteQuery(
            array(
                'indexId' => $indexId,
                'comparison' => $comparisonOperation,
                'keyList' => $keys,
                'offset' => $offset,
                'limit' => $limit,
                'columnList' => $this->getKeysByIndexId($indexId),
                'openIndexQuery' => $openIndexQuery,
                'socket' => $this,
            )
        );
        $this->addQuery($deleteQuery);

        return $deleteQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteByIndex($indexId, $comparisonOperation, array $keys, $limit = null, $offset = null)
    {
        $deleteQuery = new DeleteQuery(
            array(
                'indexId' => $indexId,
                'comparison' => $comparisonOperation,
                'keyList' => $keys,
                'offset' => $offset,
                'limit' => $limit,
                'columnList' => $this->getKeysByIndexId($indexId),
                'socket' => $this,
            )
        );
        $this->addQuery($deleteQuery);

        return $deleteQuery;
    }

    public function increment(
        array $columns, $dbName, $tableName, $indexName, $comparisonOperation, array $keys, array $valueList,
        $offset = null,
        $limit = null
    ) {
        $indexId = $this->getIndexId($dbName, $tableName, $indexName, $columns, false);
        $openIndexQuery = null;
        if ($indexId instanceof OpenIndexQuery) {
            $openIndexQuery = $indexId;
            $indexId = $openIndexQuery->getIndexId();
        }

        $incrementQuery = new IncrementQuery(
            array(
                'indexId' => $indexId,
                'comparison' => $comparisonOperation,
                'keyList' => $keys,
                'offset' => $offset,
                'limit' => $limit,
                'columnList' => $this->getKeysByIndexId($indexId),
                'openIndexQuery' => $openIndexQuery,
                'valueList' => $valueList,
                'socket' => $this,
            )
        );
        $this->addQuery($incrementQuery);

        return $incrementQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function incrementByIndex(
        $indexId, $comparisonOperation, array $keys, array $valueList, $limit = null, $offset = null
    ) {
        $incrementQuery = new IncrementQuery(
            array(
                'indexId' => $indexId,
                'comparison' => $comparisonOperation,
                'keyList' => $keys,
                'offset' => $offset,
                'limit' => $limit,
                'columnList' => $this->getKeysByIndexId($indexId),
                'valueList' => $valueList,
                'socket' => $this,
            )
        );
        $this->addQuery($incrementQuery);

        return $incrementQuery;
    }


    public function decrement(
        array $columnList, $dbName, $tableName, $indexName, $comparisonOperation, array $keys, array $valueList,
        $offset = null,
        $limit = null
    ) {
        $indexId = $this->getIndexId($dbName, $tableName, $indexName, $columnList, false);
        $openIndexQuery = null;
        if ($indexId instanceof OpenIndexQuery) {
            $openIndexQuery = $indexId;
            $indexId = $openIndexQuery->getIndexId();
        }

        $decrementQuery = new DecrementQuery(
            array(
                'indexId' => $indexId,
                'comparison' => $comparisonOperation,
                'keyList' => $keys,
                'offset' => $offset,
                'limit' => $limit,
                'columnList' => $this->getKeysByIndexId($indexId),
                'openIndexQuery' => $openIndexQuery,
                'valueList' => $valueList,
                'socket' => $this,
            )
        );
        $this->addQuery($decrementQuery);

        return $decrementQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function decrementByIndex(
        $indexId, $comparisonOperation, array $keys, array $valueList, $limit = null, $offset = null
    ) {
        $decrementQuery = new DecrementQuery(
            array(
                'indexId' => $indexId,
                'comparison' => $comparisonOperation,
                'keyList' => $keys,
                'offset' => $offset,
                'limit' => $limit,
                'columnList' => $this->getKeysByIndexId($indexId),
                'valueList' => $valueList,
                'socket' => $this,
            )
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

        $insertQuery = new InsertQuery(
            array(
                'indexId' => $indexId,
                'valueList' => $valueList,
                'openIndexQuery' => $openIndexQuery,
                'socket' => $this,
            )
        );
        $this->addQuery($insertQuery);

        return $insertQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function insertByIndex($indexId, array $valueList)
    {
        $updateQuery = new InsertQuery(
            array(
                'indexId' => $indexId,
                'valueList' => $valueList,
                'socket' => $this,
            )
        );

        $this->addQuery($updateQuery);

        return $updateQuery;
    }
}
