<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Tests\Builder;

use HS\HSInterface;
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
        $insertQueryBuilder = QueryBuilder::insert($forInsert);
        $insertQueryBuilder->fromDataBase($this->getDatabase())->fromTable(
            $this->getTableName()
        );

        $insertQuery = $this->getWriter()->addQueryBuilder($insertQueryBuilder);

        $selectQuery = $this->getWriter()->selectByIndex($insertQuery->getIndexId(), HSInterface::EQUAL, array('123'));

        $this->getWriter()->getResults();

        $insertResult = $insertQuery->getResult();
        $this->assertTrue($insertResult->isSuccessfully(), 'Fall insertQuery is not successfully done.');

        $data = $selectQuery->getResult()->getData();
        $this->assertEquals(array($forInsert), $data, 'Fall returned data not valid.');
    }
} 