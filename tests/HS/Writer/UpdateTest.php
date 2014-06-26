<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

use \HS\Tests\TestCommon;

class UpdateTest extends TestCommon
{

//    protected function setUp()
//    {
//        $this->splitSQL($this->getSqlFilePath());
//    }

    public function test1()
    {

        // update:1	=	1	100500	1	0	U	100500	42  // him
        //        1	=	1	1	    0	1	U	1	    new // my
        //        1	=	1	2	    0	1	U	2	    new


        $hsWriter = $this->getWriter();

        $indexId = $hsWriter->getIndexId(
            $this->getDatabase(),
            'hs_write',
            'PRIMARY',
            array('k', 'v')
        );
        $updateRequest = $hsWriter->update($indexId, '=', array('2'), array('2', 'new'));

        //$selectRequest = $hsWriter->select($indexId, '=', array(1));

        //print_r($hsWriter->getResponses());
        $hsWriter->getResponses();
        //$actual = $selectRequest->getResponse()->getData();
        //$this->assertEquals('text', $actual['text']);
    }
} 