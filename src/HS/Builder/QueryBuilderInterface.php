<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Builder;

use HS\QueryInterface;


/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
interface QueryBuilderInterface
{
    /**
     * @param      $indexId
     * @param null $openIndexQuery
     *
     * @return QueryInterface
     */
    public function getQuery($indexId, $openIndexQuery = null);

    /**
     * @return array
     */
    public function getColumns();

    public function fromDataBase($db);

    public function fromTable($table);

    public function fromIndex($db);

    public function limit($limit);

    public function offset($offset);

    public function where($where);

    public function andWhere($where);

    public function getDataBase();

    public function getTable();

    public function getIndex();

    public function setComparisonOperation($operation);
}