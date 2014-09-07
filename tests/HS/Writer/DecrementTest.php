<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

use HS\Result\DecrementResult;
use HS\Tests\TestCommon;

class DecrementTest extends TestCommon
{
    public function testSingleDecrementByIndexId()
    {
        $writer = $this->getWriter();

        $indexId = $writer->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'num')
        );
        $decrementQuery = $writer->decrementByIndex($indexId, '=', array(107), array(0, 1));

        $selectQuery = $writer->selectByIndex($indexId, '=', array(107));
        $writer->getResultList();

        $decrementResult = $decrementQuery->getResult();
        $this->assertTrue($decrementResult->isSuccessfully(), "Fall incrementByIndexQuery return bad status.");
        $this->assertTrue($selectQuery->getResult()->isSuccessfully(), "Fall selectByIndexQuery return bad status.");

        $this->assertTrue(
            $decrementResult->getNumberModifiedRows() > 0,
            "Fall incrementByIndexQuery didn't modified rows."
        );

        $data = $selectQuery->getResult()->getData();

        $this->assertEquals("14", $data[0]['num']);
    }

    public function testSingleDecrement()
    {
        $writer = $this->getWriter();

        $decrementQuery = $writer->decrement(
            array('key', 'num'),
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            '=',
            array(107),
            array(0, 1)
        );

        $selectQuery = $writer->selectByIndex($decrementQuery->getIndexId(), '=', array(107));
        $writer->getResultList();

        $decrementResult = $decrementQuery->getResult();
        $this->assertTrue($decrementResult->isSuccessfully(), "Fall incrementQuery return bad status.");
        $this->assertTrue($selectQuery->getResult()->isSuccessfully(), "Fall incrementQuery return bad status.");

        $this->assertTrue($decrementResult->getNumberModifiedRows() > 0, "Fall incrementQuery didn't modified rows.");

        $data = $selectQuery->getResult()->getData();

        $this->assertEquals("13", $data[0]['num']);
    }
} 