<?php
namespace HS\Builder;

use HS\HSInterface;
use HS\Query\SelectQuery;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class SelectQueryBuilder extends AbstractBuilder
{
    private $returnType = SelectQuery::ASSOC;
    private $comparisonOperation = HSInterface::EQUAL;

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return $this->constructArray;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery($indexId, $openIndexQuery = null)
    {
        return new SelectQuery(
            $indexId,
            $this->comparisonOperation,
            $this->where,
            $this->limit,
            $this->offset,
            $openIndexQuery
        );
    }

    public function ReturnAsVector()
    {
        $this->returnType = SelectQuery::VECTOR;

        return $this;
    }

    public function ReturnAsAssoc()
    {
        $this->returnType = SelectQuery::ASSOC;
    }
} 