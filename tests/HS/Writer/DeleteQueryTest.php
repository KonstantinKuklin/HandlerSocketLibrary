<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Writer;

use HS\Component\Comparison;
use HS\Result\DeleteResult;
use HS\Tests\TestCommon;

class DeleteQueryTest extends TestCommon
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
        $selectQuery = $writer->selectByIndex($indexId, Comparison::EQUAL, array(3));
        $writer->getResultList();

        /** @var DeleteResult $deleteResult */
        $deleteResult = $deleteQuery->getResult();
        $this->assertTrue($deleteResult->isSuccessfully(), "Fall deleteByIndexQuery return bad status.");
        $this->assertTrue($selectQuery->getResult()->isSuccessfully(), "Fall selectByIndexQuery return bad status.");

        $this->assertTrue($deleteResult->getNumberModifiedRows() > 0, "Fall deleteByIndexQuery didn't modified rows.");

        $data = $selectQuery->getResult()->getData();
        $this->assertTrue(empty($data), 'Fall the data won`t succesfuly deleted.');
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

        $selectQuery = $writer->selectByIndex($deleteQuery->getIndexId(), Comparison::EQUAL, array(1));
        $writer->getResultList();

        /** @var DeleteResult $deleteResult */
        $deleteResult = $deleteQuery->getResult();
        $this->assertTrue($deleteResult->isSuccessfully(), "Fall deleteQuery return bad status.");
        $this->assertTrue($selectQuery->getResult()->isSuccessfully(), "Fall selectQuery return bad status.");

        $this->assertTrue($deleteResult->getNumberModifiedRows() > 0, "Fall deleteQuery didn't modified rows.");

        $data = $selectQuery->getResult()->getData();

        $this->assertTrue(empty($data), 'Fall the data won`t succesfuly deleted.');
    }
} 