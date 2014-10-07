<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Builder;

use HS\Query\OpenIndexQuery;
use HS\Query\QueryInterface;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
interface QueryBuilderInterface
{
    /**
     * @param int                 $indexId
     * @param \HS\CommonClient    $socket
     * @param null|OpenIndexQuery $openIndexQuery
     *
     *
     * @return QueryInterface[]
     */
    public function getQuery($indexId, $socket, $openIndexQuery = null);

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