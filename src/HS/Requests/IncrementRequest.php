<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Requests;


use HS\RequestAbstract;
use HS\Responses\IncrementResponse;

class IncrementRequest extends RequestAbstract
{

    private $indexId = null;
    private $comparisonOperation = null;
    private $keys = null;
    private $limit = null;
    private $offset = null;
    private $values = null;

    /**
     * @param int    $indexId`
     * @param string $comparisonOperation
     * @param array  $keys
     * @param array  $values
     * @param int    $limit
     * @param int    $offset
     */
    public function __construct($indexId, $comparisonOperation, $keys, $values, $limit = 1, $offset = 0)
    {
        $this->indexId = $indexId;
        $this->comparisonOperation = $comparisonOperation;
        $this->keys = $keys;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->values = $values;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestParameters()
    {
        // <indexid> <op> <vlen> <v1> ... <vn> [LIM] [IN] [FILTER ...] MOD
        return array_merge(
            array(
                $this->indexId,
                $this->comparisonOperation,
                count($this->keys),
                $this->paramListToParamString($this->keys),
                $this->limit,
                $this->offset,
                '+'
            ),
            $this->values
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setResponseData($data)
    {
        $this->response = new IncrementResponse($this, $data);
    }

} 