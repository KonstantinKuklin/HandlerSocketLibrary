<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

use HS\Result\DeleteResult;
use HS\Writer;

class DeleteQuery extends ModifyQueryAbstract
{
    /**
     * {@inheritdoc}
     */
    public function getQueryParameters()
    {
        return $this->getQueryParametersWithMod(Writer::COMMAND_DELETE);
    }

    /**
     * {@inheritdoc}
     */
    public function setResultData($data)
    {
        $this->Result = new DeleteResult($this, $data, $this->openIndexQuery);
    }
}