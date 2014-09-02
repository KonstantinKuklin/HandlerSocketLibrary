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
    public function where($comparison, array $list)
    {
        $updateList = $this->constructArray;
        $this->constructArray = array_keys($updateList);
        parent::where($comparison, $list);
        $this->constructArray = $updateList;

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
            $this->limit,
            $this->offset,
            $openIndexQuery,
            array_values($this->constructArray)
        );
    }
} 