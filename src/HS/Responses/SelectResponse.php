<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Responses;

use HS\RequestAbstract;
use HS\ResponseAbstract;

class SelectResponse extends ResponseAbstract
{
    /**
     * @param RequestAbstract $request
     * @param array           $data
     * @param array           $keys
     */
    public function __construct($request, $data, $keys)
    {
        parent::__construct($request, $data);
        if ($this->isSuccessfully()) {
            // second parameter is number of count columns
            $columnCount = array_shift($data);
            $dataChunked = array_chunk($data, $columnCount);

            foreach ($dataChunked as &$row) {
                $row = array_combine($keys, $row);
            }

            $this->data = $dataChunked;
        }
    }
}