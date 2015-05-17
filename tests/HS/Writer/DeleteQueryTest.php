<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Writer;

use HS\Component\Comparison;
use HS\Result\DeleteResult;
use HS\Tests\TestWriterCommon;

class DeleteQueryTest extends TestWriterCommon
{
    public function testSingleDeleteByIndexId()
    {
        $writer = $this->getWriter();

        $indexId = $writer->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'text')
        );
        $deleteQuery = $writer->deleteByIndex($indexId, Comparison::EQUAL, array(3));
        $writer->getResultList();

        /** @var DeleteResult $deleteResult */
        $deleteResult = $deleteQuery->getResult();
        self::assertTrue($deleteResult->isSuccessfully(), "Fall deleteByIndexQuery return bad status.");
        self::assertTrue($deleteResult->getNumberModifiedRows() > 0, "Fall deleteByIndexQuery didn't modified rows.");

        self::assertTablesHSEqual(__METHOD__);
    }

    public function testSingleDelete()
    {
        $writer = $this->getWriter();

        $deleteQuery = $writer->delete(
            array('key', 'text'),
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            Comparison::EQUAL,
            array(1)
        );
        $writer->getResultList();

        /** @var DeleteResult $deleteResult */
        $deleteResult = $deleteQuery->getResult();
        self::assertTrue($deleteResult->isSuccessfully(), "Fall deleteQuery return bad status.");
        self::assertTrue($deleteResult->getNumberModifiedRows() > 0, "Fall deleteQuery didn't modified rows.");

        self::assertTablesHSEqual(__METHOD__);
    }
} 