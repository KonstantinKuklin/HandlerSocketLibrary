<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Writer;

use HS\Component\Comparison;
use HS\Result\UpdateResult;
use HS\Tests\TestCommon;

class UpdateQueryTest extends TestCommon
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

        $selectQuery = $writer->selectByIndex($indexId, Comparison::EQUAL, array(2));
        $writer->getResultList();

        /** @var UpdateResult $updateResult */
        $updateResult = $updateQuery->getResult();
        $this->assertTrue($updateResult->isSuccessfully(), "Fall updateByIndexQuery return bad status.");
        $this->assertTrue($selectQuery->getResult()->isSuccessfully(), "Fall selectByIndexQuery return bad status.");

        $this->assertTrue($updateResult->getNumberModifiedRows() > 0, "Fall updateByIndexQuery didn't modified rows.");

        $data = $selectQuery->getResult()->getData();

        $this->assertEquals('new', $data[0]['text']);
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
            array(2, 'new2')
        );

        $selectQuery = $writer->selectByIndex($updateQuery->getIndexId(), Comparison::EQUAL, array(2));
        $writer->getResultList();

        /** @var UpdateResult $updateResult */
        $updateResult = $updateQuery->getResult();
        $this->assertTrue($updateResult->isSuccessfully(), "Fall updateQuery return bad status.");
        $this->assertTrue($selectQuery->getResult()->isSuccessfully(), "Fall selectQuery return bad status.");

        $this->assertTrue($updateResult->getNumberModifiedRows() > 0, "Fall updateQuery didn't modified rows.");

        $data = $selectQuery->getResult()->getData();

        $this->assertEquals('new2', $data[0]['text']);
    }
} 