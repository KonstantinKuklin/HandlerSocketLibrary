<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Component;

class ParameterBag
{
    private $parameterList = array();

    /**
     * @param array $parameterList
     */
    public function __construct(array $parameterList)
    {
        $this->parameterList = $parameterList;
    }

    /**
     * @param string $parameterName
     * @param mixed  $defaultValue
     *
     * @return mixed
     */
    public function getParameter($parameterName, $defaultValue = null)
    {
        $checkFlag = array_key_exists($parameterName, $this->parameterList);
        if (!$checkFlag) {
            return $defaultValue;
        }

        return $this->parameterList[$parameterName];
    }

    /**
     * @param string $parameterName
     *
     * @return boolean
     */
    public function isExists($parameterName)
    {
        return array_key_exists($parameterName, $this->parameterList);
    }

    /**
     * @param string $parameterName
     * @param mixed  $value
     *
     * @return void
     */
    public function setParameter($parameterName, $value)
    {
        $this->parameterList[$parameterName] = $value;
    }

    /**
     * @param string $parameterName
     * @param mixed  $value
     *
     * @return void
     */
    public function addRowToParameter($parameterName, $value)
    {
        if (empty($this->parameterList[$parameterName])) {
            $this->parameterList[$parameterName] = array();
        }
        $this->parameterList[$parameterName][] = $value;
    }

    /**
     * @return array
     */
    public function getAsArray()
    {
        return $this->parameterList;
    }
} 