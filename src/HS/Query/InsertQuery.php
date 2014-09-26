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
    public function getQueryString()
    {
        $valueList = $this->getParameter('valueList', array());

        $queryString = sprintf(
            "%d" . Driver::DELIMITER . "+" . Driver::DELIMITER . "%d",
            $this->getIndexId(),
            count($valueList[0])
        );

        foreach ($valueList as $row) {
            $queryString .= "\t" . Driver::prepareSendDataStatic($row);
        }

        return $queryString;
    }

    /**
     * {@inheritdoc}
     */
    public function setResultData($data)
    {
        $this->getParameterBag()->setParameter(
            'resultObject',
            new InsertResult($this, $data, $this->getParameter('openIndexQuery'))
        );
    }
}