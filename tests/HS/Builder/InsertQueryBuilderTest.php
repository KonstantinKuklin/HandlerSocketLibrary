<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Tests\Builder;

use HS\Component\Comparison;
use HS\QueryBuilder;
use HS\Tests\TestCommon;

class InsertQueryBuilderTest extends TestCommon
{
    public function testSingleInsert()
    {
        $forInsert = array(
            'key' => '123',
            'date' => '0000-00-00',
            'float' => '1.02',
            'varchar' => 'char',
            'text' => 'text',
            'set' => 'a',
            'union' => 'a',
            // TODO fix bug with 'null' => null
        );
        $insertQueryBuilder = QueryBuilder::insert();
        $insertQueryBuilder->toDatabase($this->getDatabase())->toTable(
            $this->getTableName()
        )->addRow($forInsert);

        $insertQuery = $this->getWriter()->addQueryBuilder($insertQueryBuilder);

        $selectQuery = $this->getWriter()->selectByIndex($insertQuery->getIndexId(), Comparison::EQUAL, array('123'));

        $this->getWriter()->getResultList();

        $insertResult = $insertQuery->getResult();
        $this->assertTrue($insertResult->isSuccessfully(), 'Fall insertQuery is not successfully done.');

        $data = $selectQuery->getResult()->getData();
        $this->assertEquals(array($forInsert), $data, 'Fall returned data not valid.');
    }
} 