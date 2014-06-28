<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Requests;

use HS\RequestAbstract;

abstract class ModifyRequestAbstract extends RequestAbstract
{

    protected $indexId = null;
    protected $comparisonOperation = null;
    protected $keys = null;
    protected $limit = null;
    protected $offset = null;
    protected $values = null;

    /**
     * @param int    $indexId
     * @param string $comparisonOperation
     * @param array  $keys
     * @param int    $offset
     * @param int    $limit
     */
    public function __construct($indexId, $comparisonOperation, $keys, $offset = 0, $limit = 1)
    {
        $this->indexId = $indexId;
        $this->comparisonOperation = $comparisonOperation;
        $this->keys = $keys;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommonRequestParameters()
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
    public function getRequestParametersWithMod($mod)
    {
        $parametersList = $this->getCommonRequestParameters();
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