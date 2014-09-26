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
     * @return boolean
     */
    public function isSuffics()
    {
        return $this->getParameter('suffics', false);
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        $queryString = parent::getQueryString();
        $modificator = $this->getModificator();

        if ($this->isSuffics()) {
            $modificator .= '?';
        }
        $queryString .= Driver::DELIMITER . $modificator . Driver::DELIMITER;

        return $queryString;
    }
}