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
     * @param bool                $debug
     */
    public function __construct($query, $data, $keys, $returnType, $openIndexQuery = null, $debug = false)
    {
        parent::__construct($query, $data, $openIndexQuery, $debug);

        if ($this->isSuccessfully()) {

        	// Sanitize the result before parsing for anything
        	$data = Driver::prepareReceiveDataStatic($data);

        	// Remove 'response code' column, get column count
        	$response = array_shift($data);
        	$columns =  array_shift($data);

        	// Ensure at least one record returned
        	if (count($data)) {

	        	$chunkList = array_chunk($data, $columns);

                // modify row to assoc array
                if ($returnType === SelectQuery::ASSOC && !empty($keys)) {
                    foreach ($chunkList as &$row) {
                        $row = array_combine($keys, $row);
                    }
                }

                $this->data = $chunkList;
        	} else {
                $this->data = array();
            }
        }
    }
}