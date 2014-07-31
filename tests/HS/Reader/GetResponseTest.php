<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\HSReader;

use HS\HSInterface;
use HS\Reader;
use HS\ResponseAbstract;
use HS\Tests\TestCommon;

class GetResponseTest extends TestCommon
{
    public function testSelectExistedValueWithDebug()
    {
        $reader = new Reader(self::HOST, self::PORT_RO, $this->getReadPassword(), true);

        $indexId = $reader->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'date', 'float', 'varchar', 'text', 'set', 'null', 'union')
        );
        $selectRequest = $reader->select($indexId, HSInterface::EQUAL, array(42));

        $expectedResult = array(
            array(
                'key' => '42',
                'date' => '2010-10-29',
                'float' => '3.14159',
                'varchar' => 'variable length',
                'text' => "some\r\nbig\r\ntext",
                'set' => 'a,c',
                'union' => 'b',
                'null' => null
            )
        );

        $this->checkAssertionLastResponseData($reader, 'first test method with debug ', $expectedResult);
        /** @var ResponseAbstract $response */
        $response = $selectRequest->getResponse();
        $this->assertEquals(3, $reader->getCountQueries(), "The count of queries with debug is wrong.");
        $this->assertTrue($response->getTime() > 0, "Time for query is wrong.");
        $this->assertTrue($reader->getTimeQueries() > 0, "Time for all query list is wrong");
    }

    public function testSelectExistedValue()
    {
        $reader = $this->getReader();

        $indexId = $reader->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'date', 'float', 'varchar', 'text', 'set', 'null', 'union')
        );
        $selectRequest = $reader->select($indexId, HSInterface::EQUAL, array(42));

        $expectedResult = array(
            array(
                'key' => '42',
                'date' => '2010-10-29',
                'float' => '3.14159',
                'varchar' => 'variable length',
                'text' => "some\r\nbig\r\ntext",
                'set' => 'a,c',
                'union' => 'b',
                'null' => null
            )
        );

        $this->checkAssertionLastResponseData($reader, 'first test method', $expectedResult);
        $this->assertEquals(3, $reader->getCountQueries(), "The count of queries wrong.");
    }

    public function testSelectWithZeroValue()
    {
        $hsReader = $this->getReader();
        $id = $hsReader->getIndexId($this->getDatabase(), $this->getTableName(), 'PRIMARY', array('float'));
        $hsReader->select($id, HSInterface::EQUAL, array(100));

        $expectedValue = array(array('float' => 0));
        $this->checkAssertionLastResponseData($hsReader, "test", $expectedValue);
    }

    public function testSelectWithSpecialChars()
    {
        $hsReader = $this->getReader();
        $id = $hsReader->getIndexId($this->getDatabase(), $this->getTableName(), 'PRIMARY', array('text'));
        $hsReader->select($id, HSInterface::EQUAL, array(10001));

        $expectedValue = array(array("text" => "\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0A\x0B\x0C\x0D\x0E\x0F"));
        $this->checkAssertionLastResponseData($hsReader, "test", $expectedValue);;
    }
} 