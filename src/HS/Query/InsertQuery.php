<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Query;

use HS\Driver;
use HS\Result\InsertResult;

class InsertQuery extends QueryAbstract
{
    /**
     * {@inheritdoc}
     */
    public function __construct($indexId, $valueList, $socket, $openIndexQuery = null)
    {
        parent::__construct();
        $this->indexId = $indexId;
        $this->valueList = $valueList;
        $this->openIndexQuery = $openIndexQuery;
        $this->socket = $socket;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        $queryString = sprintf(
            "%d" . Driver::DELIMITER . "+" . Driver::DELIMITER . "%d",
            $this->getIndexId(),
            count($this->valueList[0])
        );

        foreach ($this->valueList as $row) {
            $queryString .= "\t" . Driver::prepareSendDataStatic($row);
        }

        return $queryString;
    }

    /**
     * {@inheritdoc}
     */
    public function setResultData($data)
    {
        $this->resultObject = new InsertResult($this, $data, $this->openIndexQuery);
    }
}