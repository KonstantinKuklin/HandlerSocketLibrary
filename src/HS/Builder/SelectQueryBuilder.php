<?php
namespace HS\Builder;

use HS\HSInterface;
use HS\Query\SelectQuery;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class SelectQueryBuilder extends QueryBuilderAbstract
{
    private $returnType = SelectQuery::ASSOC;

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
            $this->constructArray,
            array(),
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