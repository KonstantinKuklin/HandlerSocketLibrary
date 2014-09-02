<?php
namespace HS\Builder;

use HS\HSInterface;
use HS\Query\DeleteQuery;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class DeleteQueryBuilder extends QueryBuilderAbstract
{

    public function __construct()
    {
    }

    public function where($comparison, array $list)
    {
        $this->constructArray = $list;
        $this->whereComparison = $comparison;
    }

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
        return new DeleteQuery(
            $indexId,
            $this->whereComparison,
            array_values($this->constructArray),
            $this->limit,
            $this->offset,
            $openIndexQuery
        );
    }
} 