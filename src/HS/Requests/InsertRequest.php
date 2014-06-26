<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Requests;

use HS\RequestAbstract;
use HS\Responses\InsertResponse;

class InsertRequest extends RequestAbstract
{

    private $indexId = null;
    private $values = null;

    public function __construct($indexId, $values)
    {
        $this->indexId = $indexId;
        $this->values = $values;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestParameters()
    {
        return array(
            $this->indexId,
            count($this->values),
            $this->paramListToParamString($this->values),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setResponseData($data)
    {
        $this->response = new InsertResponse($this, $data);
    }
}