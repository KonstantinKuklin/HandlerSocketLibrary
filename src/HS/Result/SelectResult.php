<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Result;

use HS\Query\OpenIndexQuery;
use HS\Query\QueryAbstract;
use HS\Query\SelectQuery;

class SelectResult extends ResultAbstract
{
    /**
     * @param QueryAbstract       $query
     * @param array               $data
     * @param array               $keys
     * @param int                 $returnType
     * @param null|OpenIndexQuery $openIndexQuery
     */
    public function __construct($query, $data, $keys, $returnType, $openIndexQuery = null)
    {
        parent::__construct($query, $data, $openIndexQuery);
        if ($this->isSuccessfully()) {
            // second parameter is number of count columns
            $columnCount = array_shift($data);
            $dataChunked = array_chunk($data, $columnCount);

//            print_r($keys);
//            print_r($dataChunked);
//            echo "------------" . PHP_EOL;

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