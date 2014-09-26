<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

use HS\Driver;

abstract class ModifyQueryAbstract extends SelectQuery
{
    abstract public function getModificator();

    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        $queryString = parent::getQueryString();
        $mod = $this->getModificator();

        if ($this->isSuffix()) {
            $mod .= '?';
        }
        $queryString .= Driver::DELIMITER . $mod . Driver::DELIMITER;

        return $queryString;
    }
}