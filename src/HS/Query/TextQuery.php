<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

class TextQuery extends QueryAbstract
{
    /**
     * @param array  $parameterList
     * @param string $queryObject
     */
    public function __construct(array $parameterList, $queryObject)
    {
        parent::__construct($parameterList);
        $this->getParameterBag()->setParameter('queryClassName', $queryObject);
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        return $this->getParameter('text');
    }
}