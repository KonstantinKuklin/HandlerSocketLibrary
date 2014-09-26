<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Reader;

use HS\Errors\CommandError;
use HS\Tests\TestCommon;

class TextQueryTest extends TestCommon
{
    public function testTextQueryErrorCommand()
    {
        $reader = $this->getReader();
        try {
            $textQuery = $reader->text("dfgsdgsd", 'HS\Query\SelectQuery');
            $result = $textQuery->execute()->getResult();
        } catch (CommandError $e) {
            return true;
        }
        $this->fail('Wrong instance of error.');
    }
} 