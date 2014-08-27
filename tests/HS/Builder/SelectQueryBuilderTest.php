<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Tests\Builder;

use HS\HSInterface;
use HS\QueryBuilder;
use HS\Tests\TestCommon;

class SelectQueryBuilderTest extends TestCommon
{
    public function testSingleSelect()
    {
        $selectQueryBuilder = QueryBuilder::select(
            array('key', 'date', 'float', 'varchar', 'text', 'set', 'null', 'union')
        );
        $selectQueryBuilder->fromDataBase($this->getDatabase())->fromTable(
            $this->getTableName()
        )->setComparisonOperation(HSInterface::EQUAL)->where(42)->offset(0)->limit(1);

        $selectQuery = $this->getReader()->addQuery($selectQueryBuilder);
        $this->getReader()->getResults();

        $selectResult = $selectQuery->getResult();
        $this->assertTrue($selectResult->isSuccessfully(), 'Fall selectQuery is not successfully done.');

        $this->assertEquals(
            array(
                array(
                    'key' => '42',
                    'date' => '2010-10-29',
                    'float' => '3.14159',
                    'varchar' => 'variable length',
                    'text' => "some\r\nbig\r\ntext",
                    'set' => 'a,c',
                    'union' => 'b',
                    'null' => null
                )
            ),
            $selectResult->getData(),
            'Fall returned data not valid.'
        );

    }

} 