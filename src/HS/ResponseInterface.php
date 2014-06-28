<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;

interface ResponseInterface
{
    /**
     * @return bool
     */
    public function isSuccessfully();

    /**
     * @return RequestAbstract
     */
    public function getRequest();

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