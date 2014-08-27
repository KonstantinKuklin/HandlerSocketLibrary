<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

use HS\Result\UpdateResult;
use HS\Tests\TestCommon;

class UpdateTest extends TestCommon
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
        $updateQuery = $writer->updateByIndex($indexId, '=', array(2), array(2, 'new'));

        $selectQuery = $writer->selectByIndex($indexId, '=', array(2));
        $writer->getResults();

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
            '=',
            array(2),
            array(2, 'new2')
        );

        $selectQuery = $writer->selectByIndex($updateQuery->getIndexId(), '=', array(2));
        $writer->getResults();

        /** @var UpdateResult $updateResult */
        $updateResult = $updateQuery->getResult();
        $this->assertTrue($updateResult->isSuccessfully(), "Fall updateQuery return bad status.");
        $this->assertTrue($selectQuery->getResult()->isSuccessfully(), "Fall selectQuery return bad status.");

        $this->assertTrue($updateResult->getNumberModifiedRows() > 0, "Fall updateQuery didn't modified rows.");

        $data = $selectQuery->getResult()->getData();

        $this->assertEquals('new2', $data[0]['text']);
    }
} 