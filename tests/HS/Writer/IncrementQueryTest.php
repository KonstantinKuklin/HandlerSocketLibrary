<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Writer;

use HS\Result\IncrementResult;
use HS\Tests\TestWriterCommon;

class IncrementQueryTest extends TestWriterCommon
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
        $incrementQuery = $writer->incrementByIndex($indexId, '=', array(106), array(0, 3), false);
        $writer->getResultList();

        /** @var IncrementResult $incrementResult */
        $incrementResult = $incrementQuery->getResult();
        $this->assertTrue($incrementResult->isSuccessfully(), "Fall incrementByIndexQuery return bad status.");

        $this->assertTrue(
            $incrementResult->getNumberModifiedRows() > 0,
            "Fall incrementByIndexQuery didn't modified rows."
        );
        $this->assertTablesHSEqual(__METHOD__);
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
        $writer->getResultList();

        /** @var IncrementResult $incrementResult */
        $incrementResult = $incrementQuery->getResult();
        $this->assertTrue($incrementResult->isSuccessfully(), "Fall incrementQuery return bad status.");
        $this->assertTrue($incrementResult->getNumberModifiedRows() > 0, "Fall incrementQuery didn't modified rows.");
        $this->assertTablesHSEqual(__METHOD__);
    }
} 