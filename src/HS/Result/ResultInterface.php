<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Result;

use HS\Error;
use HS\Query\QueryAbstract;

interface ResultInterface
{
    /**
     * @return bool
     */
    public function isSuccessfully();

    /**
     * @return QueryAbstract
     */
    public function getQuery();

    /**
     * @return Error|null
     */
    public function getError();


    /**
     * @return null|string
     */
    public function getErrorMessage();

    /**
     * @return null|array
     */
    public function getData();

    /**
     * @return float
     */
    public function getTime();

    /**
     * @param float $time
     */
    public function setTime($time);
} 