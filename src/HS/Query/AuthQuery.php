<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

use HS\Driver;

class AuthQuery extends QueryAbstract
{
    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        return "A" . Driver::DELIMITER . "1" . Driver::DELIMITER . Driver::encodeData($this->getParameter('authKey'));
    }
}