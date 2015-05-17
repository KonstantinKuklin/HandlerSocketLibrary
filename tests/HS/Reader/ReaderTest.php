<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Reader;

use HS\Component\Comparison;
use HS\Component\Filter;
use HS\Exception\InvalidArgumentException;
use HS\Tests\TestCommon;

class ReaderTest extends TestCommon
{
    public function testUrlConnection()
    {
        $reader = $this->getReader();

        self::assertEquals('tcp://127.0.0.1:9998', $reader->getUrlConnection(), 'Fall wrong url connection.');
    }

    public function testIsDebug()
    {
        $reader = $this->getReader();

        self::assertFalse($reader->isDebug(), 'Fall reader with debug on.');
    }

    public function testExceptionOnMissedIndexId()
    {
        $reader = $this->getReader();
        $reader->reOpen();

        try {
            $reader->selectByIndex(
                '9000', // some random value for get exception error
                Comparison::MORE,
                array(1),
                0,
                99,
                array(new Filter(Comparison::EQUAL, 0, 1))
            );
        } catch (InvalidArgumentException $e) {
            return;
        }

        self::fail('Not fall with out opening index.');
    }

    public function testReopen()
    {
        $reader = $this->getReader();
        $reader->reOpen();

        $indexId = $reader->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'text'),
            true,
            array('num')
        );

        self::assertEquals(2, $reader->getCountQueriesInQueue(), 'Wrong count queries.');

        $selectQuery = $reader->selectByIndex(
            $indexId,
            Comparison::MORE,
            array(1),
            0,
            99,
            array(new Filter(Comparison::EQUAL, 0, 1))
        );

        self::assertEquals(3, $reader->getCountQueriesInQueue(), 'Wrong count queries.');
        $data = $selectQuery->execute()->getResult()->getData();

        // auth query + open index + select = 3
        self::assertEquals(4, $reader->getCountQueries(), 'Wrong count queries.');
        self::assertEquals(0, $reader->getCountQueriesInQueue(), 'Wrong count queries.');

        self::assertEquals(
            array(
                array(
                    'key' => '100',
                    'text' => ''
                )
            ),
            $data,
            'Got wrong data.'
        );
    }
} 