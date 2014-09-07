<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

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
    public function getQueryParameters()
    {
        $parameters = parent::getQueryParameters();
        $modificator = $this->getModificator();

        if ($this->isSuffics()) {
            $modificator .= '?';
        }

        $parameters[] = $modificator;

        return $parameters;
    }
}