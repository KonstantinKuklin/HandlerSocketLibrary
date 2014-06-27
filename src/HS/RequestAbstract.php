<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;


abstract class RequestAbstract implements RequestInterface
{
    protected $response = null;

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getRequestString()
    {
        return Driver::prepareSendDataStatic($this->getRequestParameters());
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getRequestParameters();

    /**
     * {@inheritdoc}
     */
    abstract public function setResponseData($data);
} 