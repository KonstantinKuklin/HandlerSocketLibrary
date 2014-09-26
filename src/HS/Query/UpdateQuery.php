<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

use HS\Driver;

class UpdateQuery extends ModifyQueryAbstract
{
    public function getModificator()
    {
        return 'U';
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        $queryString = parent::getQueryString();

        $valueList = $this->getParameter('valueList', array());
        if (!empty($valueList)) {
            $queryString .= Driver::prepareSendDataStatic($valueList);
        }

        return $queryString;
    }
}