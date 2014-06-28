<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;


interface RequestInterface
{
    /**
     * @return ResponseInterface
     */
    public function getResponse();

    /**
     * @return array
     */
    public function getRequestParameters();

    /**
     * @return string
     */
    public function getRequestString();

    /**
     * @param array $data
     *
     * @return void
     */
    public function setResponseData($data);
} 