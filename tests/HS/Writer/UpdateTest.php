<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

use \HS\Tests\TestCommon;

class UpdateTest extends TestCommon
{
    public function testSingleUpdate()
    {
        $writer = $this->getWriter();

        $indexId = $writer->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'text')
        );
        $updateRequest = $writer->updateByIndex($indexId, '=', array(2), array(2, 'new'));

        $selectRequest = $writer->selectByIndex($indexId, '=', array(2));
        $writer->getResults();

        $data = $selectRequest->getResult()->getData();

        $this->assertEquals('new', $data[0]['text']);
    }
} 