<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

abstract class ModifyStepQueryAbstract extends ModifyQueryAbstract
{
    /**
     * {@inheritdoc}
     */
    public function getQueryParameters()
    {
        $parameters = parent::getQueryParameters();
        $parameters = array_merge($parameters, $this->getParameter('valueList', array()));
        return $parameters;
    }
}