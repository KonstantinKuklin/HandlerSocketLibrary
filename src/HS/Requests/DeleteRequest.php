<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Requests;

use HS\Responses\DeleteResponse;
use HS\Writer;

class DeleteRequest extends ModifyRequestAbstract
{
    /**
     * {@inheritdoc}
     */
    public function getRequestParameters()
    {
        return $this->getRequestParametersWithMod(Writer::COMMAND_DELETE);
    }

    /**
     * {@inheritdoc}
     */
    public function setResponseData($data)
    {
        $this->response = new DeleteResponse($this, $data);
    }
}