<?php
namespace HS\Builder;

use HS\HSInterface;
use HS\Query\DeleteQuery;
use HS\Query\UpdateQuery;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class UpdateQueryBuilder extends QueryBuilderAbstract
{
    private $extraColumns = array();

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        $columns= array_keys($this->constructArray);

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

    /**
     * {@inheritdoc}
     */
    public function getQuery($indexId, $openIndexQuery = null)
    {
        return new UpdateQuery(
            $indexId,
            $this->whereComparison,
            $this->whereValues,
            array_values($this->constructArray),
            $this->limit,
            $this->offset,
            $openIndexQuery
        );
    }
} 