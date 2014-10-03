<?php

namespace HS;

use HS\Component\Comparison;
use HS\Component\Filter;
use HS\Exception\InvalidArgumentException;
use HS\Query\AuthQuery;
use HS\Query\OpenIndexQuery;
use HS\Query\SelectQuery;
use HS\Query\TextQuery;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class Reader extends CommonClient implements ReaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function authenticate($authKey)
    {
        if (!is_string($authKey) || is_string($authKey) && strlen($authKey) < 1) {
            throw new InvalidArgumentException(
                sprintf(
                    "Authenticate command require string password value, but got %s with value %s.",
                    gettype($authKey),
                    $authKey
                )
            );
        }
        $authQuery = new AuthQuery($authKey);
        $this->addQuery($authQuery);

        return $authQuery;
    }

    /**
     * @param string $queryText
     * @param string $queryClass
     *
     * @return TextQuery
     */
    public function text($queryText, $queryClass)
    {
        $textQuery = new TextQuery($queryText, $this, $queryClass);
        $this->addQuery($textQuery);

        return $textQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function openIndex(
        $indexId, $dbName, $tableName, $indexName, array $columnList, array $filterColumnList = array()
    ) {
        $indexQuery = new OpenIndexQuery(
            $indexId,
            $dbName,
            $tableName,
            $indexName,
            $columnList,
            $this,
            $filterColumnList
        );
        $this->addQuery($indexQuery);
        $this->setKeysToIndexId($indexId, $columnList);

        return $indexQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function getIndexId(
        $dbName, $tableName, $indexName, array $columnList, $returnOnlyId = true, array $filterColumnList = array()
    ) {
        $columnsToSearch = implode('', $columnList) . implode('', $filterColumnList);

        $indexMapValue = $dbName . $tableName . $indexName . $columnsToSearch;
        if (!$indexId = $this->getIndexIdFromArray($indexMapValue)) {
            $indexId = $this->getCurrentIterator();
            $openIndexQuery = $this->openIndex(
                $indexId,
                $dbName,
                $tableName,
                $indexName,
                $columnList,
                $filterColumnList
            );
            $this->addIndexIdToArray($indexMapValue, $indexId);

            // return OpenIndexQuery if we can
            if (!$returnOnlyId) {
                return $openIndexQuery;
            }
        }

        return $indexId;
    }

    /**
     * {@inheritdoc}
     */
    public function selectByIndex(
        $indexId, $comparisonOperation, array $keys, $offset = null, $limit = null, array $filterList = array()
    ) {
        $selectQuery = new SelectQuery(
            $indexId,
            $comparisonOperation,
            $keys,
            $this,
            $this->getKeysByIndexId($indexId),
            $offset, $limit, null, null, $filterList
        );

        $this->addQuery($selectQuery);

        return $selectQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function selectInByIndex($indexId, $in, $offset = null, $limit = null, array $filterList = array())
    {
        if ($limit !== null && $limit < count($in)) {
            throw new InvalidArgumentException("Limit must be > count of in");
        }

        $selectQuery = new SelectQuery(
            $indexId,
            Comparison::EQUAL,
            array(1),
            $this,
            $this->getKeysByIndexId($indexId),
            $offset,
            ($limit !== null) ? $limit : count($in),
            null, $in, $filterList
        );

        $this->addQuery($selectQuery);

        return $selectQuery;
    }

    /**
     * @param array    $columns
     * @param string   $dbName
     * @param string   $tableName
     * @param string   $indexName
     * @param string   $comparisonOperation
     * @param array    $keys
     * @param int      $offset
     * @param int      $limit
     * @param array    $filterColumnList
     * @param Filter[] $filterList
     *
     * @return SelectQuery
     */
    public function select(
        $columns, $dbName, $tableName, $indexName, $comparisonOperation, $keys, $offset = null, $limit = null,
        array $filterColumnList = array(), array $filterList = array()
    ) {
        $indexId = $this->getIndexId($dbName, $tableName, $indexName, $columns, false, $filterColumnList);
        $openIndexQuery = null;
        if ($indexId instanceof OpenIndexQuery) {
            $openIndexQuery = $indexId;
            $indexId = $openIndexQuery->getIndexId();
        }

        $selectQuery = new SelectQuery(
            $indexId,
            $comparisonOperation,
            $keys,
            $this,
            $this->getKeysByIndexId($indexId),
            $offset, $limit, $openIndexQuery, null, $filterList
        );

        $this->addQuery($selectQuery);

        return $selectQuery;
    }

    /**
     * @param array  $columns
     * @param string $dbName
     * @param string $tableName
     * @param string $indexName
     * @param array  $in
     * @param int    $offset
     * @param int    $limit
     *
     * @param array  $filterList
     *
     * @throws InvalidArgumentException
     * @return SelectQuery
     */
    public function selectIn(
        $columns, $dbName, $tableName, $indexName, $in, $offset = null, $limit = null, array $filterList = array()
    ) {
        $indexId = $this->getIndexId($dbName, $tableName, $indexName, $columns, false);
        $openIndexQuery = null;
        if ($indexId instanceof OpenIndexQuery) {
            $openIndexQuery = $indexId;
            $indexId = $openIndexQuery->getIndexId();
        }

        /** @var int $indexId */

        if ($limit !== null && $limit < count($in)) {
            throw new InvalidArgumentException("Limit must be > count of in");
        }

        $selectQuery = new SelectQuery(
            $indexId,
            Comparison::EQUAL,
            array(1),
            $this,
            $this->getKeysByIndexId($indexId),
            $offset,
            ($limit !== null) ? $limit : count($in),
            $openIndexQuery, $in, $filterList
        );

        $this->addQuery($selectQuery);

        return $selectQuery;
    }
}
