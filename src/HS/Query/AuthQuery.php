<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

class AuthQuery extends QueryAbstract
{
    /**
     * {@inheritdoc}
     */
    public function getQueryParameters()
    {
        return array(
            'A',
            '1', // <atyp>
            $this->getParameter('authKey') // <akey>
        );
    }
}