<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

interface QueryInterface
{
    /**
     * @return \HS\Result\ResultInterface
     */
    public function getResult();

    /**
     * @return array
     */
    public function getQueryParameters();

    /**
     * @return string
     */
    public function getQueryString();

    /**
     * @param array $data
     *
     * @return void
     */
    public function setResultData($data);
} 