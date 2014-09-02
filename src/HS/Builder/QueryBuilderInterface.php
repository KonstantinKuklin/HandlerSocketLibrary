<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Builder;

use HS\HSInterface;
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

    public function fromIndex($index);

    public function limit($limit);

    public function offset($offset);

    public function where($comparison, array $list);

    public function andWhere($key, $comparison, $value, $type = HSInterface::FILTER_TYPE_SKIP);

    public function getDataBase();

    public function getTable();

    public function getIndex();

    public function isValid();

    public function getFilterColumns();
}