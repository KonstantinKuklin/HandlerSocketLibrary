<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Reader;

use HS\Component\Comparison;
use HS\Component\Filter;
use HS\Exception\WrongParameterException;
use HS\Tests\TestCommon;

class ReaderTest extends TestCommon
{
    public function testUrlConnection()
    {
        $reader = $this->getReader();

        $this->assertEquals('tcp://127.0.0.1:9998', $reader->getUrlConnection(), 'Fall wrong url connection.');
    }

    public function testIsDebug()
    {
        $reader = $this->getReader();

        $this->assertFalse($reader->isDebug(), 'Fall reader with debug on.');
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
        } catch (WrongParameterException $e) {
            return true;
        }

        $this->fail('Not fall with out opening index.');

        return true;
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

        $this->assertEquals(2, $reader->getCountQueriesInQueue(), 'Wrong count queries.');

        $selectQuery = $reader->selectByIndex(
            $indexId,
            Comparison::MORE,
            array(1),
            0,
            99,
            array(new Filter(Comparison::EQUAL, 0, 1))
        );

        $this->assertEquals(3, $reader->getCountQueriesInQueue(), 'Wrong count queries.');
        $data = $selectQuery->execute()->getResult()->getData();

        // auth query + open index + select = 3
        $this->assertEquals(4, $reader->getCountQueries(), 'Wrong count queries.');
        $this->assertEquals(0, $reader->getCountQueriesInQueue(), 'Wrong count queries.');

        $this->assertEquals(
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