<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

use HS\QueryAbstract;

abstract class ModifyQueryAbstract extends QueryAbstract
{

    protected $indexId = null;
    protected $comparisonOperation = null;
    protected $keys = null;
    protected $limit = null;
    protected $offset = null;
    protected $values = null;
    protected $openIndexQuery = null;

    /**
     * @param int                 $indexId
     * @param string              $comparisonOperation
     * @param array               $keys
     * @param int                 $offset
     * @param int                 $limit
     * @param null|OpenIndexQuery $openIndexQuery
     */
    public function __construct($indexId, $comparisonOperation, $keys, $offset = 0, $limit = 1, $openIndexQuery = null)
    {
        $this->indexId = $indexId;
        $this->comparisonOperation = $comparisonOperation;
        $this->keys = $keys;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->openIndexQuery = $openIndexQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommonQueryParameters()
    {
        // <indexid> <op> <vlen> <v1> ... <vn> [LIM] [IN] [FILTER ...] MOD
        return array_merge(
            array(
                $this->indexId,
                $this->comparisonOperation,
                count($this->keys)
            ),
            $this->keys,
            array(
                $this->limit,
                $this->offset,
            )
        );
    }

    /**
     * @param string $mod
     *
     * @return array
     */
    public function getQueryParametersWithMod($mod)
    {
        $parametersList = $this->getCommonQueryParameters();
        $parametersList[] = $mod;

        return array_merge($parametersList, $this->values);
    }

    /**
     * @param array $values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }
} 