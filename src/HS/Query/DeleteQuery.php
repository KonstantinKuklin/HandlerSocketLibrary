<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

class DeleteQuery extends ModifyQueryAbstract
{
    public function getModificator()
    {
        return 'D';
    }
}