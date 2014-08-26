<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Query;

use HS\QueryAbstract;
use HS\Result\InsertResult;

class InsertQuery extends QueryAbstract
{
    private $indexId = null;
    private $values = null;
    private $openIndexQuery = null;

    /**
     * @param int   $indexId
     * @param array $values
     * @param null  $openIndexQuery
     */
    public function __construct($indexId, $values, $openIndexQuery = null)
    {
        $this->indexId = $indexId;
        $this->values = $values;
        $this->openIndexQuery = $openIndexQuery;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryParameters()
    {
        return array_merge(
            array(
                $this->indexId,
                '+',
                count($this->values)
            ),
            $this->values
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setResultData($data)
    {
        $this->Result = new InsertResult($this, $data, $this->openIndexQuery);
    }
}