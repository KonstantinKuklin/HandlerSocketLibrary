<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Requests;


use HS\RequestAbstract;
use HS\Responses\DeleteResponse;

class DeleteRequest extends RequestAbstract
{

    private $indexId = null;
    private $comparisonOperation = null;
    private $keys = null;
    private $limit = null;
    private $offset = null;

    /**
     * @param int    $indexId
     * @param string $comparisonOperation
     * @param array  $keys
     * @param int    $limit
     * @param int    $offset
     */
    public function __construct($indexId, $comparisonOperation, $keys, $limit = 1, $offset = 0)
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
    public function getRequestParameters()
    {
        // <indexid> <op> <vlen> <v1> ... <vn> [LIM] [IN] [FILTER ...] MOD
        return array(
            $this->indexId,
            $this->comparisonOperation,
            count($this->keys),
            $this->paramListToParamString($this->keys),
            $this->limit,
            $this->offset,
            'D'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setResponseData($data)
    {
        $this->response = new DeleteResponse($this, $data);
    }

} 