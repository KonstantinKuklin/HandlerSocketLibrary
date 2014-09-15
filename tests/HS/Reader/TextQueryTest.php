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
        $textQuery = $reader->text("dfgsdgsd", 'HS\Query\SelectQuery');
        $result = $textQuery->execute()->getResult();
        if (!($result->getError() instanceof CommandError)) {
            $this->fail('Wrong instance of error.');
        }

        $this->assertEquals('cmd', $result->getErrorMessage(), 'Wrong message.');
        $this->assertEquals(false, $result->isSuccessfully(), 'Wrong query type');
        $this->assertEquals($textQuery, $result->getQuery(), 'Wrong query class');
    }
} 