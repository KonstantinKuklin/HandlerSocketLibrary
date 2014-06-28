<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Requests;

use HS\Responses\DecrementResponse;
use HS\Writer;

class DecrementRequest extends ModifyRequestAbstract
{
    /**
     * @param int    $indexId
     * @param string $comparisonOperation
     * @param array  $keys
     * @param array  $values
     * @param int    $limit
     * @param int    $offset
     */
    public function __construct($indexId, $comparisonOperation, $keys, $values, $offset = 0, $limit = 1)
    {
        parent::__construct($indexId, $comparisonOperation, $keys, $offset, $limit);
        $this->setValues($values);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestParameters()
    {
        return $this->getRequestParametersWithMod(Writer::COMMAND_DECREMENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setResponseData($data)
    {
        $this->response = new DecrementResponse($this, $data);
    }
} 