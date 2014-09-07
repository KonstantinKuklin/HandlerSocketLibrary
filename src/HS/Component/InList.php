<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Component;

use HS\Exception\WrongParameterException;

class InList
{
    private $position;
    private $keyList = array();

    /**
     * @param int   $position
     * @param array $keyList
     *
     * @throws WrongParameterException
     */
    public function __construct($position, array $keyList)
    {
        if (!is_numeric($position)) {
            throw new WrongParameterException("ColumnNumber must be a number");
        }
        $this->position = $position;
        $this->keyList = $keyList;
    }

    /**
     * @return array
     */
    public function getKeyList()
    {
        return $this->keyList;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->keyList);
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
} 