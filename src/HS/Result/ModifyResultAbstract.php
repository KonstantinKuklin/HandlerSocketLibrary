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
        if (!$this->isSuccessfully()) {
            return 0;
        }

        if ($this->modifyRows === null) {
            $this->modifyRows = substr($this->data, 2, 1);
        }

        return $this->modifyRows;
    }
} 