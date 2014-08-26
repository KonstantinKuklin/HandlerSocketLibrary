<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Result;

use HS\QueryAbstract;
use HS\Query\SelectQuery;
use HS\ResultAbstract;

class SelectResult extends ResultAbstract
{
    /**
     * @param QueryAbstract $Query
     * @param array         $data
     * @param array         $keys
     * @param int           $returnType
     * @param null          $openIndexQuery
     */
    public function __construct($Query, $data, $keys, $returnType, $openIndexQuery = null)
    {
        parent::__construct($Query, $data, $openIndexQuery);
        if ($this->isSuccessfully()) {
            // second parameter is number of count columns
            $columnCount = array_shift($data);
            $dataChunked = array_chunk($data, $columnCount);

            // modify row to assoc array
            if ($returnType === SelectQuery::ASSOC) {
                foreach ($dataChunked as &$row) {
                    $row = array_combine($keys, $row);
                }
            }

            $this->data = $dataChunked;
        }
    }
}