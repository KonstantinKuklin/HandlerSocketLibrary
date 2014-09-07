<?php
namespace HS\Builder;

use HS\Exception\WrongParameterException;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class DeleteQueryBuilder extends FindQueryBuilderAbstract
{

    public function __construct()
    {
        parent::__construct(array());
    }

    /**
     * @return string
     */
    public function getQueryClassPath()
    {
        return 'HS\Query\DeleteQuery';
    }

    /**
     * @param string $comparison
     * @param array  $list
     *
     * @return $this
     * @throws WrongParameterException
     */
    public function where($comparison, array $list)
    {
        $columnList = array_keys($list);
        $this->getParameterBag()->setParameter('columnList', $columnList);
        parent::where($comparison, $list);

        return $this;
    }
}