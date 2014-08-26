<?php
namespace HS\Builder;

use HS\HSInterface;
use HS\Query\DeleteQuery;
use HS\Query\InsertQuery;
use HS\Query\UpdateQuery;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class InsertQueryBuilder extends AbstractBuilder
{
    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return array_keys($this->constructArray);
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