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
            $this->whereComparison,
            $this->whereValues,
            $this->limit,
            $this->offset,
            $this->constructArray,
            $this->in,
            $openIndexQuery
        );
    }

    public function where($comparison, array $list)
    {
        parent::where($comparison, $list);

        // check is ordered list of keys
        for ($i = 0, $countWhere = count($list); $i < $countWhere; $i++) {
            $key = $this->constructArray[$i];
            if (!isset($list[$key])) {
                throw new \Exception("The key`s must be set with out skip on select( key1, key2). Where(key2,key1)");
            }
            $this->whereValues[] = $list[$key];
        }

        return $this;
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