<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

use HS\Result\SelectResult;

class TextQuery extends QueryAbstract
{
    private $text = '';

    /**
     * @param string $text
     * @param        $socket
     * @param string $queryObject
     */
    public function __construct($text, $socket, $queryObject)
    {
        parent::__construct();
        $this->text = $text;
        $this->socket = $socket;

        $this->queryClassName = $queryObject;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        return $this->text;
    }

    /**
     * {@inheritdoc}
     */
    public function setResultData($data, $debug = false)
    {
        if ($this->getQueryClassName() === 'HS\Query\SelectQuery') {
            $this->resultObject = new SelectResult(
                $this,
                $data,
                array(),
                SelectQuery::VECTOR,
                $this->openIndexQuery,
                $debug
            );
        } else {
            $this->setResultObject(self::$queryResultMap[$this->getQueryClassName()], $data, $debug);

        }
    }
}