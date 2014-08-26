<?php
namespace HS\Builder;

use HS\HSInterface;
use HS\Query\DecrementQuery;
use HS\Query\DeleteQuery;
use HS\Query\IncrementQuery;
use HS\Query\UpdateQuery;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class DecrementQueryBuilder extends AbstractBuilder
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
        return new DecrementQuery(
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