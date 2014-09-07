<?php

namespace HS\Builder;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class DecrementQueryBuilder extends IncrementQueryBuilder
{
    /**
     * @return string
     */
    public function getQueryClassPath()
    {
        return 'HS\Query\DecrementQuery';
    }
} 