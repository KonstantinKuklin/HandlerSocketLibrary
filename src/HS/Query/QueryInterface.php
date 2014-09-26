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
     * @return string
     */
    public function getQueryString();

    /**
     * @param array $data
     *
     * @return void
     */
    public function setResultData($data);

    /**
     * @return int
     */
    public function getIndexId();

    /**
     * @return boolean
     */
    public function isSuffix();

    /**
     * @return $this
     * @throws \HS\Exception\InvalidArgumentException
     */
    public function execute();
} 