<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;

use HS\Exceptions\WrongParameterException;

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

    /**
     * @param int $indexId
     *
     * @throws Exceptions\WrongParameterException
     * @return int
     */
    protected function validateIndexId($indexId)
    {
        if (!$this->validateInt($indexId)) {
            $this->getWrongParameterException("Wrong indexId value, must be integer > 0.", $indexId);
        }

        return $indexId;
    }

    /**
     * @param string $dbName
     *
     * @throws Exceptions\WrongParameterException
     * @return string
     */
    protected function validateDbName($dbName)
    {
        if (!$this->validateString($dbName)) {
            $this->getWrongParameterException("Wrong dbName value, must be string and length > 0.", $dbName);
        }

        return $dbName;
    }

    /**
     * @param string $indexName
     *
     * @throws Exceptions\WrongParameterException
     * @return string
     */
    protected function validateIndexName($indexName)
    {
        if (!$this->validateString($indexName)) {
            $this->getWrongParameterException("Wrong indexName value, must be string and length > 0.", $indexName);
        }

        return $indexName;
    }

    /**
     * @param string $tableName
     *
     * @throws Exceptions\WrongParameterException
     * @return string
     */
    protected function validateTableName($tableName)
    {
        if (!$this->validateString($tableName)) {
            $this->getWrongParameterException("Wrong tableName value, must be string and length > 0.", $tableName);
        }

        return $tableName;
    }

    /**
     * @param string $message
     * @param mixed  $data
     *
     * @throws Exceptions\WrongParameterException
     */
    protected function getWrongParameterException($message, $data)
    {
        throw new WrongParameterException(
            $message . sprintf(
                "Got %s with values %s.",
                gettype($data),
                (is_array($data) || is_object($data)) ? print_r($data, true) : $data
            )
        );
    }

    /**
     * @param string $data
     *
     * @return bool
     */
    protected function validateString($data)
    {
        if (!is_string($data) || is_string($data) && strlen($data) < 1) {
            return false;
        }

        return true;
    }

    /**
     * @param int $data
     *
     * @return bool
     */
    protected function validateInt($data)
    {
        if (is_int($data) && $data > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param array $data
     * @param bool  $checkArrayCount
     *
     * @return bool
     */
    protected function validateArray($data, $checkArrayCount = false)
    {
        if (!is_array($data)) {
            return false;
        }

        if ($checkArrayCount && count($data) == 0) {
            return false;
        }

        foreach ($data as $row) {
            if (is_array($row) || is_object($row)) {
                return false;
            }
        }

        return true;
    }
} 