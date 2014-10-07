<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Result;

use HS\Driver;
use HS\Error;
use HS\Errors\AuthenticationError;
use HS\Errors\ColumnParseError;
use HS\Errors\CommandError;
use HS\Errors\ComparisonOperatorError;
use HS\Errors\FilterColumnError;
use HS\Errors\FilterTypeError;
use HS\Errors\IndexOverFlowError;
use HS\Errors\InListSizeError;
use HS\Errors\InternalMysqlError;
use HS\Errors\KeyIndexError;
use HS\Errors\KeyLengthError;
use HS\Errors\LockTableError;
use HS\Errors\OpenTableError;
use HS\Errors\ReadOnlyError;
use HS\Errors\UnknownError;
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

    /** @var null|int */
    protected $modifyRows = null;

    /** @var OpenIndexQuery|null */
    protected $openIndexQuery = null;

    /**
     * @param QueryInterface      $query
     * @param string              $data
     * @param null|OpenIndexQuery $openIndexQuery
     *
     * @throws \HS\Error
     */
    public function __construct(QueryInterface $query, $data, $openIndexQuery = null)
    {
        $this->openIndexQuery = $openIndexQuery;
        $this->query = $query;

        $code = substr($data, 0, 1);
        $this->setCode($code);

        if ($code !== "0") {
            // 4 because we need to skip 0 \t 1 \t
            $error = substr($data, 4);
            $this->throwErrorClass($error);
        } else {
            $this->data = $data;
        }
    }

    /**
     * @return bool
     */
    public function isSuccessfully()
    {
        // check depended result
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

    protected function throwErrorClass($error)
    {
        $errorClass = null;
        switch ($error) {
            case 'cmd':
            case 'syntax':
            case 'notimpl':
                $errorClass = new CommandError($error);
                break;
            case 'authtype':
            case 'unauth':
                $errorClass = new AuthenticationError($error);
                break;
            case 'open_table':
                $errorClass = new OpenTableError($error);
                break;
            case 'tblnum':
            case 'stmtnum':
                $errorClass = new IndexOverFlowError($error);
                break;
            case 'invalueslen':
                $errorClass = new InListSizeError($error);
                break;
            case 'filtertype':
                $errorClass = new FilterTypeError($error);
                break;
            case 'filterfld':
                $errorClass = new FilterColumnError($error);
                break;
            case 'lock_tables':
                $errorClass = new LockTableError($error);
                break;
            case 'modop':
                $errorClass = new LockTableError($error);
                break;
            case 'idxnum':
                $errorClass = new KeyIndexError($error);
                break;
            case 'kpnum':
            case 'klen':
                $errorClass = new KeyLengthError($error);
                break;
            case 'op':
                $errorClass = new ComparisonOperatorError($error);
                break;
            case 'readonly':
                $errorClass = new ReadOnlyError($error);
                break;
            case 'fld':
                $errorClass = new ColumnParseError($error);
                break;
            case 'filterblob': // unknown error TODO
            default:
                // Errors with wrong data
                if (is_numeric($error)) {
                    $errorClass = new InternalMysqlError($error);
                } else {
                    $errorClass = new UnknownError($error);
                }
                break;
        }
        throw $errorClass;
    }
} 