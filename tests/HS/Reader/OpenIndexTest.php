<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\HSReader;


use HS\Tests\TestCommon;
use HS\Reader;

class OpenIndexTest extends TestCommon
{

    public function testWithOutAuth()
    {
        $hsReader = new Reader(TestCommon::IP, TestCommon::PORT_RO);

        $indexRequest = $hsReader->openIndex(
            1,
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'date', 'float', 'varchar', 'text', 'set', 'null', 'union')
        );

        $hsReader->getResponses();
        $this->assertFalse($indexRequest->getResponse()->isSuccessfully(), 'Fail if auth not needed.');
    }

    public function testWithAuth()
    {
        $hsReader = $this->getReader();

        $indexRequest = $hsReader->openIndex(
            1,
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'date', 'float', 'varchar', 'text', 'set', 'null', 'union')
        );

        $hsReader->getResponses();
        $this->assertTrue($indexRequest->getResponse()->isSuccessfully(), 'Fail auth works wrong.');
    }
} 