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
        if (!empty($this->valueList)) {
            $queryString .= Driver::prepareSendDataStatic($this->valueList);
        }

        return $queryString;
    }
}