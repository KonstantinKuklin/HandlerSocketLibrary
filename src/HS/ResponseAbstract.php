<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;

use HS\Errors\AuthenticationError;
use HS\Errors\CommandNotFoundError;
use HS\Errors\OpenTableError;

abstract class ResponseAbstract implements ResponseInterface
{
    protected $request = null;

    /** @var null|integer */
    protected $code = null;

    /** @var null|\Hs\Error */
    protected $error = null;

    protected $data = null;

    /**
     * @param RequestInterface $request
     * @param array            $data
     */
    public function __construct(RequestInterface $request, &$data)
    {
        $this->request = $request;
        $code = array_shift($data);
        $this->setCode($code);

        if ($this->code != 0) {
            /* inside data array with indexes:
                0 - always integer 1
                1 - human readable error message
            */
            $error = $data[1];
            switch ($error) {
                case 'cmd':
                    $this->error = new CommandNotFoundError($error);
                    break;
                case 'unauth':
                    $this->error = new AuthenticationError($error);
                    break;
                case 'open_table':
                    $this->error = new OpenTableError($error);
                    break;
                default:
                    $this->error = new Error($error);
                    break;
            }

        }

        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function isSuccessfully()
    {
        if ($this->code === 0) {
            return true;
        }

        return false;
    }

    /**
     * @return RequestAbstract
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Error|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param int $code
     */
    protected function setCode($code)
    {
        $this->code = (int)$code;
    }

    /**
     * @return null|string
     */
    public function getErrorMessage()
    {
        if ($this->error === null) {
            return null;
        }

        return $this->error->getMessage();
    }

    /**
     * @return null|array
     */
    public function getData()
    {
        return $this->data;
    }

} 