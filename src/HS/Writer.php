<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;

use HS\Query\InsertQuery;

class Writer extends Reader implements WriterHSInterface
{
    const COMMAND_UPDATE = 'U';
    const COMMAND_DELETE = 'D';
    const COMMAND_INCREMENT = '+';
    const COMMAND_DECREMENT = '-';

    /**
     * {@inheritdoc}
     */
    public function updateByIndex($indexId, $comparisonOperation, $keys, $values, $limit = 1, $offset = 0)
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

    /**
     * {@inheritdoc}
     */
    public function deleteByIndex($indexId, $comparisonOperation, $keys, $limit = 1, $offset = 0)
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

    /**
     * {@inheritdoc}
     */
    public function incrementByIndex($indexId, $comparisonOperation, $keys, $values, $limit = 1, $offset = 0)
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

    /**
     * {@inheritdoc}
     */
    public function decrementByIndex($indexId, $comparisonOperation, $keys, $values, $limit = 1, $offset = 0)
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
     * @param int        $limit
     * @param int        $offset
     *
     * @return null|QueryInterface
     */
    private function modifyByIndexQuery(
        $queryClassName, $indexId, $comparisonOperation, $keys, $values, $limit = 1, $offset = 0
    ) {
        $className = $queryClassName . 'Query';
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
                $values,
                $offset,
                $limit
            );
        }

        $this->addQuery($modifyQuery);

        return $modifyQuery;
    }
}
