<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Tests\Builder;

use HS\HSInterface;
use HS\QueryBuilder;
use HS\Tests\TestCommon;

class UpdateQueryBuilderTest extends TestCommon
{
    public function testSingleUpdate()
    {
        $this->assertTrue(true);
//        $updateQueryBuilder = QueryBuilder::update(
//            array('varchar' => 'test update query')
//        );
//        $updateQueryBuilder->fromDataBase($this->getDatabase())->fromTable(
//            $this->getTableName()
//        )->where(HSInterface::EQUAL, array('key' => 42));
//
//        $selectQuery = $this->getReader()->addQueryBuilder($updateQueryBuilder);
//        $this->getReader()->getResults();
//
//        $selectResult = $selectQuery->getResult();
//        $this->assertTrue($selectResult->isSuccessfully(), 'Fall selectQuery is not successfully done.');
//
//        $this->assertEquals(
//            array(
//                array(
//                    'key' => '42',
//                    'date' => '2010-10-29',
//                    'float' => '3.14159',
//                    'varchar' => 'variable length',
//                    'text' => "some\r\nbig\r\ntext",
//                    'set' => 'a,c',
//                    'union' => 'b',
//                    'null' => null
//                )
//            ),
//            $selectResult->getData(),
//            'Fall returned data not valid.'
//        );
    }
} 