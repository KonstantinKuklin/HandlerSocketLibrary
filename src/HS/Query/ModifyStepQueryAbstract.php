<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

use HS\Driver;

abstract class ModifyStepQueryAbstract extends ModifyQueryAbstract
{
    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        $queryString = parent::getQueryString();
        if (($valueList = $this->getParameter('valueList', array())) && !empty($valueList)) {
            $queryString .= Driver::prepareSendDataStatic($valueList);
        }

        return $queryString;
    }
}