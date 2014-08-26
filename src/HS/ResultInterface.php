<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;

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
} 