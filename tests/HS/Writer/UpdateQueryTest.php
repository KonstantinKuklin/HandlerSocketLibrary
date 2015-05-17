<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Writer;

use HS\Component\Comparison;
use HS\Result\SelectResult;
use HS\Result\UpdateResult;
use HS\Tests\TestWriterCommon;

class UpdateQueryTest extends TestWriterCommon
{
    public function testSingleUpdateByIndexId()
    {
        $writer = $this->getWriter();

        $indexId = $writer->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'text')
        );
        $updateQuery = $writer->updateByIndex($indexId, Comparison::EQUAL, array(2), array(2, 'new'));
        $writer->getResultList();

        $updateResult = $updateQuery->getResult();
        self::assertTrue($updateResult->isSuccessfully(), "Fall updateByIndexQuery return bad status.");
        self::assertTrue($updateResult->getNumberModifiedRows() > 0, "Fall updateByIndexQuery didn't modified rows.");
        self::assertTablesHSEqual(__METHOD__);
    }

    public function testSingleUpdate()
    {
        $writer = $this->getWriter();

        $updateQuery = $writer->update(
            array('key', 'text'),
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            Comparison::EQUAL,
            array(2),
            array(2, 'new next')
        );
        $writer->getResultList();

        /** @var UpdateResult $updateResult */
        $updateResult = $updateQuery->getResult();
        self::assertTrue($updateResult->isSuccessfully(), "Fall updateQuery return bad status.");
        self::assertTrue($updateResult->getNumberModifiedRows() > 0, "Fall updateQuery didn't modified rows.");
        self::assertTablesHSEqual(__METHOD__);
    }

    public function testSingleUpdateWithSuffix()
    {
        $writer = $this->getWriter();

        $updateQuery = $writer->update(
            array('key', 'text'),
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            Comparison::EQUAL,
            array(2),
            array(2, 'new suffix'),
            true
        );
        $writer->getResultList();

        $updateResult = $updateQuery->getResult();
        self::assertTrue($updateResult->isSuccessfully(), "Fall updateQuery return bad status.");

        $dataUpdateSelectResult = $updateResult->getData();
        if (!($updateResult instanceof SelectResult)) {
            $this->fail("Returned not a select result object.");
        }
        self::assertEquals(array(array('key' => 2, 'text' => '')), $dataUpdateSelectResult);
        self::assertTablesHSEqual(__METHOD__);
    }
} 