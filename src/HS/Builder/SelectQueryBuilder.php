<?php
namespace HS\Builder;

use HS\Query\SelectQuery;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class SelectQueryBuilder extends FindQueryBuilderAbstract
{

    /**
     * @return $this
     */
    public function returnAsVector()
    {
        $this->returnType = SelectQuery::VECTOR;

        return $this;
    }

    /**
     * @return $this
     */
    public function returnAsAssoc()
    {
        $this->returnType = SelectQuery::ASSOC;

        return $this;
    }

    /**
     * @return string
     */
    public function getQueryClassPath()
    {
        return 'HS\Query\SelectQuery';
    }
}