<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Result;

class ModifyResultAbstract extends ResultAbstract implements ResultInterface
{

    /**
     * @return int
     */
    public function getNumberModifiedRows()
    {
        if ($this->isSuccessfully()) {
            return $this->data[1];
        }

        return 0;
    }
} 