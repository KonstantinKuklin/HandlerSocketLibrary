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
        $updateRequest = $writer->update($indexId, '=', array(2), array(2, 'new'));

        $selectRequest = $writer->select($indexId, '=', array(2));
        $writer->getResponses();

        $data = $selectRequest->getResponse()->getData();

        $this->assertEquals('new', $data[0]['text']);
    }
} 