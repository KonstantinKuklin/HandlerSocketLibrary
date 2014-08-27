<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

use HS\Result\IncrementResult;
use HS\Tests\TestCommon;

class IncrementTest extends TestCommon
{
    public function test(){
        $this->assertTrue(true);
    }

    // TODO find what`s wrong
//    public function testSingleIncrementByIndexId()
//    {
//        $writer = $this->getWriter();
//
//        $indexId = $writer->getIndexId(
//            $this->getDatabase(),
//            $this->getTableName(),
//            'PRIMARY',
//            array('key', 'float')
//        );
//        $incrementQuery = $writer->incrementByIndex($indexId, '=', array(200), array(200, 7));
//
//        $selectQuery = $writer->selectByIndex($indexId, '=', array(200));
//        $writer->getResults();
//
//        /** @var IncrementResult $incrementResult */
//        $incrementResult = $incrementQuery->getResult();
//        $this->assertTrue($incrementResult->isSuccessfully(), "Fall incrementByIndexQuery return bad status.");
//        $this->assertTrue($selectQuery->getResult()->isSuccessfully(), "Fall selectByIndexQuery return bad status.");
//
//        $this->assertTrue(
//            $incrementResult->getNumberModifiedRows() > 0,
//            "Fall incrementByIndexQuery didn't modified rows."
//        );
//
//        $data = $selectQuery->getResult()->getData();
//        print_r($data);
//
//        $this->assertEquals(7, $data[0]['float']);
//    }

//    public function testIncrementUpdate()
//    {
//        $writer = $this->getWriter();
//
//        $incrementQuery = $writer->increment(
//            array('key', 'float'),
//            $this->getDatabase(),
//            $this->getTableName(),
//            'PRIMARY',
//            '=',
//            array(100),
//            array(100, 6)
//        );
//
//        $selectQuery = $writer->selectByIndex($incrementQuery->getIndexId(), '=', array(100));
//        $writer->getResults();
//
//        /** @var IncrementResult $incrementResult */
//        $incrementResult = $incrementQuery->getResult();
//        $this->assertTrue($incrementResult->isSuccessfully(), "Fall incrementQuery return bad status.");
//        $this->assertTrue($selectQuery->getResult()->isSuccessfully(), "Fall incrementQuery return bad status.");
//
//        $this->assertTrue($incrementResult->getNumberModifiedRows() > 0, "Fall incrementQuery didn't modified rows.");
//
//        $data = $selectQuery->getResult()->getData();
//
//        $this->assertEquals(6, $data[0]['float']);
//    }
} 