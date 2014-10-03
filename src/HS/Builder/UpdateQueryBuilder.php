<?php

namespace HS\Builder;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class UpdateQueryBuilder extends FindQueryBuilderAbstract
{
    /**
     * @param array $updateList
     */
    public function __construct(array $updateList)
    {
        $columnList = array_keys($updateList);
        $valueList = array_values($updateList);
        parent::__construct($columnList);
        $this->valueList = $valueList;
    }

    /**
     * @return string
     */
    public function getQueryClassPath()
    {
        return 'HS\Query\UpdateQuery';
    }
}