<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;


interface QueryInterface
{
    /**
     * @return ResultInterface
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