<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

use HS\QueryAbstract;
use HS\Result\TextResult;

class TextQuery extends QueryAbstract
{
    private $text = null;

    /**
     * @param string $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryParameters()
    {
        return array($this->text);
    }

    /**
     * {@inheritdoc}
     */
    public function setResultData($data)
    {
        $this->result = new TextResult($this, $data);
    }
} 