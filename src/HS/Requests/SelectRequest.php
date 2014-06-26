<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Requests;

use HS\RequestAbstract;
use HS\Responses\SelectResponse;

class SelectRequest extends RequestAbstract
{

    private $indexId = null;
    private $comparisonOperation = null;
    private $keys = null;
    private $limit = null;
    private $offset = null;
    private $indexColumns = null;

    public function __construct($indexId, $comparisonOperation, $keys, $limit = 0, $offset = 0, $indexColumns)
    {
        $this->indexId = $indexId;
        $this->comparisonOperation = $comparisonOperation;
        $this->keys = $keys;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->indexColumns = $indexColumns;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestParameters()
    {
        return array(
            $this->indexId,
            $this->comparisonOperation,
            count($this->keys),
            $this->paramListToParamString($this->keys),
            $this->limit,
            $this->offset
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setResponseData($data)
    {
        $this->response = new SelectResponse($this, $data, $this->indexColumns);
    }
}