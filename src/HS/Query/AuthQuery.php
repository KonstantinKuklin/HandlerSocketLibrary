<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

use HS\QueryAbstract;
use HS\Result\AuthResult;

class AuthQuery extends QueryAbstract
{
    private $authKey = null;

    /**
     * @param string $authKey
     */
    public function __construct($authKey)
    {
        $this->authKey = $authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryParameters()
    {
        return array(
            'A',
            '1', // <atyp>
            $this->authKey // <akey>
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setResultData($data)
    {
        $this->Result = new AuthResult($this, $data);
    }

} 