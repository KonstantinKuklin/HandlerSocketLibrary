<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Writer;

use HS\Result\InsertResult;
use HS\Tests\TestWriterCommon;

class InsertQueryTest extends TestWriterCommon
{
    public function testSingleInsertByIndexId()
    {
        $writer = $this->getWriter();

        $indexId = $writer->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'date', 'float', 'varchar', 'text', 'set', 'union')
        );
        $insertQuery = $writer->insertByIndex(
            $indexId,
            array(array('467', '0000-00-01', '1.02', 'char', 'text467', '1', '1'))
        );
        $writer->getResultList();

        $insertResult = $insertQuery->getResult();
        $this->assertTrue($insertResult->isSuccessfully(), "Fall updateByIndexQuery return bad status.");
        $this->assertTablesHSEqual(__METHOD__);
    }

    public function testSingleInsert()
    {
        $writer = $this->getWriter();

        $insertQuery = $writer->insert(
            array('key', 'date', 'float', 'varchar', 'text', 'set', 'union'),
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array(array('468', '0000-00-01', '1.02', 'char', 'text468', '1', '1'))
        );
        $writer->getResultList();

        /** @var InsertResult $insertResult */
        $insertResult = $insertQuery->getResult();
        $this->assertTrue($insertResult->isSuccessfully(), "Fall updateQuery return bad status.");
        $this->assertTablesHSEqual(__METHOD__);
    }
}