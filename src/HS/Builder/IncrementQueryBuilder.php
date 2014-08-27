<?php
namespace HS\Builder;

use HS\HSInterface;
use HS\Query\DeleteQuery;
use HS\Query\IncrementQuery;
use HS\Query\UpdateQuery;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class IncrementQueryBuilder extends QueryBuilderAbstract
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
        return new IncrementQuery(
            $indexId,
            $this->comparisonOperation,
            $this->where,
            array_values($this->constructArray),
            $this->limit,
            $this->offset,
            $openIndexQuery
        );
    }
} 