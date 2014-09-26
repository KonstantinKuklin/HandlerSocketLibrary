<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;

use HS\Exception\InvalidArgumentException;

class Validator
{

    /**
     * @param int $indexId
     *
     * @throws InvalidArgumentException
     * @return void
     */
    public static function validateIndexId($indexId)
    {
        if (!self::validateInt($indexId)) {
            self::getInvalidArgumentException("Wrong indexId value, must be integer >= 0.", $indexId);
        }
    }

    /**
     * @param string $dbName
     *
     * @throws InvalidArgumentException
     * @return void
     */
    public static function validateDbName($dbName)
    {
        if (!self::validateString($dbName)) {
            self::getInvalidArgumentException("Wrong dbName value, must be string and length > 0.", $dbName);
        }
    }

    /**
     * @param string $indexName
     *
     * @throws InvalidArgumentException
     * @return void
     */
    public static function validateIndexName($indexName)
    {
        if (!self::validateString($indexName)) {
            self::getInvalidArgumentException("Wrong indexName value, must be string and length > 0.", $indexName);
        }
    }

    /**
     * @param string $tableName
     *
     * @throws InvalidArgumentException
     * @return void
     */
    public static function validateTableName($tableName)
    {
        if (!self::validateString($tableName)) {
            self::getInvalidArgumentException("Wrong tableName value, must be string and length > 0.", $tableName);
        }
    }

    /**
     * @param array $columnList
     *
     * @throws InvalidArgumentException
     * @return void
     */
    public static function validateColumnList(array $columnList)
    {
        if (!self::validateArray($columnList, true)) {
            self::getInvalidArgumentException("Wrong columnList, must be array and length > 0.", $columnList);
        }
    }

    /**
     * @param string $message
     * @param mixed  $data
     *
     * @throws InvalidArgumentException
     */
    public static function getInvalidArgumentException($message, $data)
    {
        throw new InvalidArgumentException(
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
    public static function validateString($data)
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
    public static function validateInt($data)
    {
        if (is_int($data) && $data >= 0) {
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
    public static function validateArray(array $data, $checkArrayCount = false)
    {
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