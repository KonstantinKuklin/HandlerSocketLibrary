<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

use HS\Result\IncrementResult;
use HS\Writer;

class IncrementQuery extends ModifyQueryAbstract
{
    /**
     * @param int    $indexId
     * @param string $comparisonOperation
     * @param array  $keys
     * @param array  $values
     * @param int    $offset
     * @param int    $limit
     * @param null   $openIndexQuery
     */
    public function __construct(
        $indexId, $comparisonOperation, $keys, $values, $offset = 0, $limit = 1, $openIndexQuery = null
    ) {
        parent::__construct($indexId, $comparisonOperation, $keys, $offset, $limit, $openIndexQuery);
        $this->setValues($values);
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryParameters()
    {
        return $this->getQueryParametersWithMod(Writer::COMMAND_INCREMENT);
    }

    /**
     * {@inheritdoc}
     */
    public function setResultData($data)
    {
        $this->Result = new IncrementResult($this, $data, $$this->openIndexQuery);
    }

} 