<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Reader;

use HS\Component\Comparison;
use HS\Component\Filter;
use HS\Query\SelectQuery;
use HS\Reader;
use HS\Tests\TestCommon;
use HS\Exception\InvalidArgumentException;

class SelectQueryTest extends TestCommon
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
        $selectRequest = $reader->selectByIndex($indexId, Comparison::EQUAL, array(42));

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
        /** @var \HS\Result\ResultAbstract $response */
        $response = $selectRequest->getResult();
        self::assertEquals(3, $reader->getCountQueries(), "The count of queries with debug is wrong.");
        self::assertTrue($response->getTime() > 0, "Time for query is wrong.");
        self::assertTrue($reader->getTimeQueries() > 0, "Time for all query list is wrong");
        $reader->close();
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
        $selectQuery = $reader->selectByIndex($indexId, Comparison::EQUAL, array(42));

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
        self::assertEquals(3, $reader->getCountQueries(), "The count of queries wrong.");
    }

    public function testSelectExistedValueAsVector()
    {
        $reader = $this->getReader();

        $indexId = $reader->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'date', 'float', 'varchar', 'text', 'set', 'null', 'union')
        );
        $selectRequest = $reader->selectByIndex($indexId, Comparison::EQUAL, array(42));
        $selectRequest->setReturnType(SelectQuery::VECTOR);

        $expectedResult = array(
            array(
                '42',
                '2010-10-29',
                '3.14159',
                'variable length',
                "some\r\nbig\r\ntext",
                'a,c',
                null,
                'b'
            )
        );

        $this->checkAssertionLastResponseData($reader, 'first test method', $expectedResult);
        self::assertEquals(3, $reader->getCountQueries(), "The count of queries wrong.");
    }

    public function testSelectWithZeroValue()
    {
        $hsReader = $this->getReader();
        $id = $hsReader->getIndexId($this->getDatabase(), $this->getTableName(), 'PRIMARY', array('float'));
        $hsReader->selectByIndex($id, Comparison::EQUAL, array(100));

        $expectedValue = array(array('float' => 0));
        $this->checkAssertionLastResponseData($hsReader, "test", $expectedValue);
    }

    public function testSelectWithSpecialChars()
    {
        $hsReader = $this->getReader();
        $id = $hsReader->getIndexId($this->getDatabase(), $this->getTableName(), 'PRIMARY', array('text'));
        $hsReader->selectByIndex($id, Comparison::EQUAL, array(10001));

        $expectedValue = array(array("text" => "\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0A\x0B\x0C\x0D\x0E\x0F"));
        $this->checkAssertionLastResponseData($hsReader, "test", $expectedValue);;
    }

    public function testSelectInExistedValue()
    {
        $reader = $this->getReader();

        $selectQuery = $reader->selectIn(
            array('key', 'date', 'float', 'varchar', 'text', 'set', 'null', 'union'),
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array(42, 100),
            0,
            99
        );

        $this->getReader()->getResultList();

        $selectResult = $selectQuery->getResult();

        self::assertTrue($selectResult->isSuccessfully(), 'Fail selectIn query returned error code.');
        self::assertEquals(
            array(
                array(
                    'key' => '42',
                    'date' => '2010-10-29',
                    'float' => '3.14159',
                    'varchar' => 'variable length',
                    'text' => "some\r\nbig\r\ntext",
                    'set' => 'a,c',
                    'union' => 'b',
                    'null' => null
                ),
                array(
                    'key' => '100',
                    'date' => '0000-00-00',
                    'float' => '0',
                    'varchar' => '',
                    'text' => '',
                    'set' => '',
                    'union' => '',
                    'null' => null
                )
            ),
            $selectResult->getData(),
            "Fall selectIn query returned invalid data."
        );
    }

    public function testSelectInByIndexExistedValue()
    {
        $reader = $this->getReader();

        $indexId = $reader->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'date', 'float', 'varchar', 'text', 'set', 'null', 'union')
        );
        $selectQuery = $reader->selectInByIndex($indexId, array(42, 100));

        $this->getReader()->addQuery($selectQuery);
        $this->getReader()->getResultList();

        $selectResult = $selectQuery->getResult();

        self::assertTrue($selectResult->isSuccessfully(), 'Fail selectIn query returned error code.');
        self::assertEquals(
            array(
                array(
                    'key' => '42',
                    'date' => '2010-10-29',
                    'float' => '3.14159',
                    'varchar' => 'variable length',
                    'text' => "some\r\nbig\r\ntext",
                    'set' => 'a,c',
                    'union' => 'b',
                    'null' => null
                ),
                array(
                    'key' => '100',
                    'date' => '0000-00-00',
                    'float' => '0',
                    'varchar' => '',
                    'text' => '',
                    'set' => '',
                    'union' => '',
                    'null' => null
                )
            ),
            $selectResult->getData(),
            "Fall selectIn query returned invalid data."
        );
    }

    public function testSelectExistedValueWithFilter()
    {
        $reader = $this->getReader();

        $selectQuery = $reader->select(
            array('key', 'text'),
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            Comparison::MORE,
            array('1'),
            0,
            99,
            array('num'),
            array(new Filter(Comparison::EQUAL, 0, '3'))
        );

        $this->getReader()->getResultList();

        $selectResult = $selectQuery->getResult();

        self::assertTrue($selectResult->isSuccessfully(), 'Fail select query with filter returned error code.');
        self::assertEquals(
            array(
                array("key" => '101', "text" => 'text101'),
                array("key" => '102', "text" => 'text102'),
                array("key" => '103', "text" => 'text103'),
            ),
            $selectResult->getData(),
            "Fall select query with filter returned invalid data."
        );
    }

    public function testSelectInExistedValueWithFilter()
    {
        $reader = $this->getReader();

        $indexId = $reader->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'text'),
            true,
            array('num')
        );
        $selectQuery = $reader->selectInByIndex(
            $indexId,
            array(42, 100),
            0,
            99,
            array(new Filter(Comparison::EQUAL, 0, 1))
        );

        $this->getReader()->addQuery($selectQuery);
        $this->getReader()->getResultList();

        $selectResult = $selectQuery->getResult();

        self::assertTrue($selectResult->isSuccessfully(), 'Fail selectIn query with filter returned error code.');
        self::assertEquals(
            array(
                array(
                    'key' => '100',
                    'text' => ''
                )
            ),
            $selectResult->getData(),
            "Fall selectIn query with filter returned invalid data."
        );
    }

    public function testSelectByIndexExistedValueWithFilter()
    {
        $reader = $this->getReader();

        $indexId = $reader->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'text'),
            true,
            array('num')
        );
        $selectQuery = $reader->selectByIndex(
            $indexId,
            Comparison::MORE,
            array(1),
            0,
            99,
            array(new Filter(Comparison::EQUAL, 0, 1))
        );

        $this->getReader()->addQuery($selectQuery);
        $this->getReader()->getResultList();

        $selectResult = $selectQuery->getResult();

        self::assertTrue($selectResult->isSuccessfully(), 'Fail selectByIndex query with filter returned error code.');
        self::assertEquals(
            array(
                array(
                    'key' => '100',
                    'text' => ''
                )
            ),
            $selectResult->getData(),
            "Fall selectByIndex query with filter returned invalid data."
        );
    }

    public function testSelectByIndexExistedValueExecuted()
    {
        $reader = $this->getReader();

        $indexId = $reader->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'text'),
            true,
            array('num')
        );
        $selectQuery = $reader->selectByIndex(
            $indexId,
            Comparison::MORE,
            array(1),
            0,
            99,
            array(new Filter(Comparison::EQUAL, 0, 1))
        );

        $selectResult = $selectQuery->execute()->getResult();

        self::assertTrue($selectResult->isSuccessfully(), 'Fail selectByIndex query executed by self.');
        self::assertEquals(
            array(
                array(
                    'key' => '100',
                    'text' => ''
                )
            ),
            $selectResult->getData(),
            "Fall selectByIndex query executed by self."
        );
    }

    public function testSelectByIndexWithIncorrectLength()
    {
        $reader = $this->getReader();

        $indexId = $reader->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'text'),
            true,
            array('num')
        );

        try {
            $selectQuery = $reader->selectByIndex(
                $indexId,
                Comparison::MORE,
                array(1),
                10,
                0,
                array(new Filter(Comparison::EQUAL, 0, 1))
            );
        } catch (InvalidArgumentException $e) {
            return;
        }

        $this->fail('Incorrect behavior on limit < 1');

    }

    public function testSelectByIndexExistedValueWithSmallReadLength()
    {
        $reader = new Reader(self::HOST, self::PORT_RO, $this->getReadPassword(), true, 5);

        $indexId = $reader->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'text'),
            true,
            array('num')
        );
        $selectQuery = $reader->selectByIndex(
            $indexId,
            Comparison::MORE,
            array(1),
            0,
            99,
            array(new Filter(Comparison::EQUAL, 0, 1))
        );

        $selectResult = $selectQuery->execute()->getResult();

        $this->assertTrue($selectResult->isSuccessfully(), 'Fail selectByIndex query executed by self.');
        $this->assertEquals(
            array(
                array(
                    'key' => '100',
                    'text' => ''
                )
            ),
            $selectResult->getData(),
            "Fall selectByIndex query executed by self."
        );

        $reader->close();
    }
} 