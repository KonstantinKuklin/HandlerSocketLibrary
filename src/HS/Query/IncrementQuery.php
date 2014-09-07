<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

class IncrementQuery extends ModifyStepQueryAbstract
{
    public function getModificator()
    {
        return '+';
    }
}