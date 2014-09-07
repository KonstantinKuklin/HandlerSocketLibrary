<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

class TextQuery extends QueryAbstract
{
    /**
     * {@inheritdoc}
     */
    public function getQueryParameters()
    {
        return array($this->getParameter('text'));
    }
} 