<?php
namespace HS\Builder;

use HS\Query\InsertQuery;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class InsertQueryBuilder extends QueryBuilderAbstract
{
    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return array_keys($this->constructArray);
    }

    public function where($comparison, array $list){

    }

    /**
     * {@inheritdoc}
     */
    public function getQuery($indexId, $openIndexQuery = null)
    {
        return new InsertQuery(
            $indexId,
            array_values($this->constructArray),
            $openIndexQuery
        );
    }
} 