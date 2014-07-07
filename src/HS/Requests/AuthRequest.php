<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Requests;

use HS\RequestAbstract;
use HS\Responses\AuthResponse;

class AuthRequest extends RequestAbstract
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
    public function getRequestParameters()
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
    public function setResponseData($data)
    {
        $this->response = new AuthResponse($this, $data);
    }

} 