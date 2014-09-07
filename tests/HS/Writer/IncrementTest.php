<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

use HS\Result\IncrementResult;
use HS\Tests\TestCommon;

class IncrementTest extends TestCommon
{
    public function testSingleIncrementByIndexId()
    {
        $writer = $this->getWriter();

        $indexId = $writer->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'num')
        );
        $incrementQuery = $writer->incrementByIndex($indexId, '=', array(106), array(0, 3));

        $selectQuery = $writer->selectByIndex($indexId, '=', array(106));
        $writer->getResultList();

        /** @var IncrementResult $incrementResult */
        $incrementResult = $incrementQuery->getResult();
        $this->assertTrue($incrementResult->isSuccessfully(), "Fall incrementByIndexQuery return bad status.");
        $this->assertTrue($selectQuery->getResult()->isSuccessfully(), "Fall selectByIndexQuery return bad status.");

        $this->assertTrue(
            $incrementResult->getNumberModifiedRows() > 0,
            "Fall incrementByIndexQuery didn't modified rows."
        );

        $data = $selectQuery->getResult()->getData();

        $this->assertEquals("18", $data[0]['num']);
    }

    public function testSingleIncrement()
    {
        $writer = $this->getWriter();

        $incrementQuery = $writer->increment(
            array('key', 'num'),
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            '=',
            array(106),
            array(0, 5)
        );

        $selectQuery = $writer->selectByIndex($incrementQuery->getIndexId(), '=', array(106));
        $writer->getResultList();

        /** @var IncrementResult $incrementResult */
        $incrementResult = $incrementQuery->getResult();
        $this->assertTrue($incrementResult->isSuccessfully(), "Fall incrementQuery return bad status.");
        $this->assertTrue($selectQuery->getResult()->isSuccessfully(), "Fall incrementQuery return bad status.");

        $this->assertTrue($incrementResult->getNumberModifiedRows() > 0, "Fall incrementQuery didn't modified rows.");

        $data = $selectQuery->getResult()->getData();

        $this->assertEquals("23", $data[0]['num']);
    }
} 