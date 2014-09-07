<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Query;

use HS\Result\InsertResult;

class InsertQuery extends QueryAbstract
{
    /**
     * {@inheritdoc}
     */
    public function getQueryParameters()
    {
        $valueList = $this->getParameter('valueList', array());

        $returnList = array(
            $this->getIndexId(),
            '+',
            count($valueList[0])
        );

        foreach ($valueList as $row) {
            $returnList = array_merge($returnList, $row);
        }

        return $returnList;
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