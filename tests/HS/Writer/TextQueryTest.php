<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Writer;

use HS\Tests\TestCommon;

class TextQueryTest extends TestCommon
{
    public function testSelectTextQuery()
    {
        $writer = $this->getWriter();

        $indexId = $writer->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'text')
        );
        $textQuery = $writer->text(sprintf("%d\t=\t1\t105\t1\t0", $indexId), 'HS\Query\SelectQuery');
        $writer->getResultList();

        $this->assertTrue($textQuery->getResult()->isSuccessfully(), "Fall selectByIndexQuery return bad status.");
        $data = $textQuery->getResult()->getData();
        $this->assertEquals(array(array('105', 'text105')), $data, 'Wrong data was returned with textQuery');
    }
} 