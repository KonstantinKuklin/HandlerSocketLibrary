<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Result;

use HS\Driver;
use HS\Query\OpenIndexQuery;
use HS\Query\QueryAbstract;
use HS\Query\SelectQuery;

class SelectResult extends ResultAbstract
{
    /**
     * @param QueryAbstract       $query
     * @param string              $data
     * @param array               $keys
     * @param int                 $returnType
     * @param null|OpenIndexQuery $openIndexQuery
     */
    public function __construct($query, $data, $keys, $returnType, $openIndexQuery = null)
    {
        parent::__construct($query, $data, $openIndexQuery);

        if ($this->isSuccessfully()) {
            // if returned only numbers without data
            if (strlen($data) !== 3) {
                // second parameter is number of count columns
                $columnCount = substr($data, 2, 1);

                $listData = Driver::prepareReceiveDataStatic(substr($data, 4));

                $dataChunked = array_chunk($listData, $columnCount);

                // modify row to assoc array
                if ($returnType === SelectQuery::ASSOC && !empty($keys)) {
                    foreach ($dataChunked as &$row) {
                        $row = array_combine($keys, $row);
                    }
                }

                $this->data = $dataChunked;
            } else {
                $this->data = array();
            }
        }
    }
}