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
     * {@inheritdoc}
     */
    abstract public function getRequestParameters();

    /**
     * {@inheritdoc}
     */
    abstract public function setResponseData($data);

    /**
     * @param array $paramList
     *
     * @return string
     */
    protected function paramListToParamString($paramList)
    {
        return implode(',', $paramList);
    }
} 