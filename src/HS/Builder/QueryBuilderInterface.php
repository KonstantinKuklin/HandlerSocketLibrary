<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Builder;

use HS\Query\QueryInterface;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
interface QueryBuilderInterface
{
    /**
     * @param int  $indexId
     * @param null $openIndexQuery
     *
     * @return QueryInterface
     */
    public function getQuery($indexId, $openIndexQuery = null);

    /**
     * @param int $limit
     *
     * @return QueryBuilderInterface
     */
    public function limit($limit);

    /**
     * @param int $offset
     *
     * @return QueryBuilderInterface
     */
    public function offset($offset);

    /**
     * @return boolean
     */
    public function isValid();

    /**
     * @return string|null
     */
    public function getDatabase();

    /**
     * @return string|null
     */
    public function getTable();

    /**
     * @return string|null
     */
    public function getIndex();

    /**
     * @return array
     */
    public function getColumnList();

    /**
     * @return array
     */
    public function getFilterList();

    /**
     * @return array
     */
    public function getFilterColumnList();

}