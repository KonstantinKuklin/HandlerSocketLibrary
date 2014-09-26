<?php

namespace HS;

use HS\Component\Comparison;
use HS\Component\Filter;
use HS\Component\InList;
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
        $authQuery = new AuthQuery(array('authKey' => trim($authKey), 'socket' => $this));
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
        $textQuery = new TextQuery(array('text' => $queryText, 'socket' => $this), $queryClass);
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
            array(
                'indexId' => $indexId,
                'dbName' => $dbName,
                'tableName' => $tableName,
                'indexName' => $indexName,
                'columnList' => $columnList,
                'filterColumnList' => $filterColumnList,
                'socket' => $this,
            )
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
            array(
                'indexId' => $indexId,
                'comparison' => $comparisonOperation,
                'keyList' => $keys,
                'offset' => $offset,
                'limit' => $limit,
                'columnList' => $this->getKeysByIndexId($indexId),
                'filterList' => $filterList,
                'socket' => $this,
            )
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
            array(
                'indexId' => $indexId,
                'comparison' => Comparison::EQUAL,
                'keyList' => array(1),
                'offset' => $offset,
                'limit' => ($limit !== null) ? $limit : count($in),
                'columnList' => $this->getKeysByIndexId($indexId),
                'inKeyList' => new InList(0, $in),
                'filterList' => $filterList,
                'socket' => $this,
            )
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
            array(
                'indexId' => $indexId,
                'comparison' => $comparisonOperation,
                'keyList' => $keys,
                'offset' => $offset,
                'limit' => $limit,
                'columnList' => $this->getKeysByIndexId($indexId),
                'openIndexQuery' => $openIndexQuery,
                'filterList' => $filterList,
                'socket' => $this,
            )
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
     * @throws InvalidArgumentException
     * @return SelectQuery
     */
    public function selectIn(
        $columns, $dbName, $tableName, $indexName, $in, $offset = null, $limit = null
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
            array(
                'indexId' => $indexId,
                'comparison' => Comparison::EQUAL,
                'keyList' => array(1),
                'offset' => $offset,
                'limit' => ($limit !== null) ? $limit : count($in),
                'columnList' => $this->getKeysByIndexId($indexId),
                'inKeyList' => new InList(0, $in),
                'socket' => $this,
            )
        );

        $this->addQuery($selectQuery);

        return $selectQuery;
    }
}
