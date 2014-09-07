<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Result;

use HS\Error;
use HS\Errors\AuthenticationError;
use HS\Errors\AutoIncrementSetError;
use HS\Errors\CommandNotFoundError;
use HS\Errors\OpenTableError;
use HS\Query\OpenIndexQuery;
use HS\Query\QueryAbstract;
use HS\Query\QueryInterface;

abstract class ResultAbstract implements ResultInterface
{
    /** @var QueryInterface|null */
    protected $query = null;

    /** @var null|integer */
    protected $code = null;

    /** @var null|\Hs\Error */
    protected $error = null;

    /** @var array|null */
    protected $data = null;

    /** @var double */
    protected $time = 0;

    private $openIndexQuery = null;

    /**
     * @param QueryInterface      $query
     * @param array               $data
     * @param null|OpenIndexQuery $openIndexQuery
     */
    public function __construct(QueryInterface $query, &$data, $openIndexQuery = null)
    {
        $this->openIndexQuery = $openIndexQuery;
        $this->query = $query;
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
                case '121':
                    $this->error = new AutoIncrementSetError($error);
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
        if ($this->openIndexQuery !== null && !$this->openIndexQuery->getResult()->isSuccessfully()) {
            return false;
        }

        if ($this->code === 0) {
            return true;
        }

        return false;
    }

    /**
     * @return QueryAbstract
     */
    public function getQuery()
    {
        return $this->query;
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

    /**
     * @param float $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return float
     */
    public function getTime()
    {
        return $this->time;
    }
} 