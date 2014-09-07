<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

class DecrementQuery extends ModifyStepQueryAbstract
{
    public function getModificator()
    {
        return '-';
    }
}