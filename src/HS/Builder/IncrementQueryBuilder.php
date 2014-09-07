<?php

namespace HS\Builder;

use HS\Exception\WrongParameterException;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class IncrementQueryBuilder extends UpdateQueryBuilder
{
    /**
     * @param array $incrementList
     *
     * @throws WrongParameterException
     */
    public function __construct(array $incrementList)
    {
        $columnList = array();
        $valueList = array();
        foreach ($incrementList as $key => $value) {
            if (is_numeric($value) && !is_numeric($key)) {
                $columnList[] = $key;
                $valueList[] = $value;
            } elseif (!is_numeric($value) && is_numeric($key)) {
                $columnList[] = $value;
                $valueList[] = 1;
            } else {
                throw new WrongParameterException("Wrong increment parameter.");
            }

        }

        parent::__construct(array_combine($columnList, $valueList));
    }

    /**
     * @return string
     */
    public function getQueryClassPath()
    {
        return 'HS\Query\IncrementQuery';
    }
} 