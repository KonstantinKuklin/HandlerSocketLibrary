<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Writer;

use HS\Component\Comparison;
use HS\Result\InsertResult;
use HS\Tests\TestCommon;

class InsertQueryTest extends TestCommon
{
    public function testInsertByIndexId()
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

        $selectQuery = $writer->selectByIndex($indexId, Comparison::EQUAL, array(467));
        $writer->getResultList();

        $insertResult = $insertQuery->getResult();
        $this->assertTrue($insertResult->isSuccessfully(), "Fall updateByIndexQuery return bad status.");
        $this->assertTrue($selectQuery->getResult()->isSuccessfully(), "Fall selectByIndexQuery return bad status.");

        $data = $selectQuery->getResult()->getData();

        $this->assertEquals('text467', $data[0]['text']);
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

        $selectQuery = $writer->selectByIndex($insertQuery->getIndexId(), Comparison::EQUAL, array(468));
        $writer->getResultList();

        /** @var InsertResult $insertResult */
        $insertResult = $insertQuery->getResult();
        $this->assertTrue($insertResult->isSuccessfully(), "Fall updateQuery return bad status.");
        $this->assertTrue($selectQuery->getResult()->isSuccessfully(), "Fall selectQuery return bad status.");

        $data = $selectQuery->getResult()->getData();

        $this->assertEquals(
            array(
                array(
                    'key' => '468',
                    'date' => '0000-00-01',
                    'float' => '1.02',
                    'varchar' => 'char',
                    'text' => 'text468',
                    'union' => 'a',
                    'set' => 'a'
                )
            ),
            $data
        );
    }
}