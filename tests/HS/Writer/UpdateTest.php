<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

use \HS\Tests\TestCommon;

class UpdateTest extends TestCommon
{
    public function test1()
    {
        $hsWriter = $this->getWriter();

        $indexId = $hsWriter->getIndexId(
            $this->getDatabase(),
            $this->getTableName(),
            'PRIMARY',
            array('key', 'text')
        );
        $updateRequest = $hsWriter->update($indexId, '=', array('2'), array('2', 'new'));

        $selectRequest = $hsWriter->select($indexId, '=', array(2));
        $hsWriter->getResponses();

        $data = $selectRequest->getResponse()->getData();

        $this->assertEquals('new', $data[0]['text']);
    }
} 