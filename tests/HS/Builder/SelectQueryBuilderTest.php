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
        )->where(HSInterface::EQUAL, array('key' => 42));

        $selectQuery = $this->getReader()->addQueryBuilder($selectQueryBuilder);
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

    public function testSingleSelectExceptionWhere()
    {
        $selectQueryBuilder = QueryBuilder::select(
            array('key', 'date', 'float', 'varchar', 'text', 'set', 'null', 'union')
        );

        try {
            $selectQueryBuilder->fromDataBase($this->getDatabase())->fromTable(
                $this->getTableName()
            )->where(HSInterface::EQUAL, array('float' => 42));
        } catch (\Exception $e) {
            return true;
        }
        $this->fail('Fail where not throw exception on wrong key position.');
    }

    public function testSingleSelectWithFilter()
    {
        $selectQueryBuilder = QueryBuilder::select(
            array('key', 'date', 'varchar', 'text', 'set', 'union')
        );
        $selectQueryBuilder->fromDataBase($this->getDatabase())->fromTable(
            $this->getTableName()
        )->where(HSInterface::MORE, array('key' => 2))
            ->andWhere('float', HSInterface::EQUAL, 3);

        $selectQuery = $this->getReader()->addQueryBuilder($selectQueryBuilder);
        $this->getReader()->getResults();

        $selectResult = $selectQuery->getResult();
        $this->assertTrue($selectResult->isSuccessfully(), 'Fall selectQuery is not successfully done.');

        $this->assertEquals(
            array(
                array(
                    'key' => '3',
                    'date' => '0000-00-00',
                    'varchar' => '',
                    'text' => '',
                    'set' => '',
                    'union' => ''
                )
            ),
            $selectResult->getData(),
            'Fall returned data not valid.'
        );
    }

    public function testBugSingleSelectWithWhereIn()
    {
        $selectQueryBuilder = QueryBuilder::select(
            array('key', 'date', 'float', 'varchar', 'text', 'set', 'null', 'union')
        );
        $selectQueryBuilder->fromDataBase($this->getDatabase())->fromTable(
            $this->getTableName()
        )->whereIn('key', array(42, 4));

        $selectQuery = $this->getReader()->addQueryBuilder($selectQueryBuilder);
        $this->getReader()->getResults();

        $selectResult = $selectQuery->getResult();
        $this->assertFalse($selectResult->isSuccessfully(), 'Bug with working IN.');
    }
} 