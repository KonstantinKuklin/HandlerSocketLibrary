<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

use HS\Driver;

class AuthQuery extends QueryAbstract
{
    private $authKey = '';

    /**
     * @param string $authKey
     */
    public function __construct($authKey)
    {
        parent::__construct();
        $this->authKey = $authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        return 'A' . Driver::DELIMITER . '1' . Driver::DELIMITER . Driver::encodeData($this->authKey);
    }
}