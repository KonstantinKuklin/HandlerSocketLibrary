<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Builder;

use HS\QueryBuilder;
use HS\Tests\TestWriterCommon;

class InsertQueryBuilderTest extends TestWriterCommon
{
    public function testBuilderSingleInsert()
    {
        $insertQueryBuilder = QueryBuilder::insert();
        $insertQueryBuilder->toDatabase($this->getDatabase())->toTable(
            $this->getTableName()
        )->toIndex('PRIMARY')->addRow(
            array(
                'key' => '123',
                'date' => '0000-00-00',
                'float' => '1.02',
                'varchar' => 'char',
                'text' => 'text',
                'set' => 'a',
                'union' => 'a',
                // TODO fix bug with 'null' => null
            )
        );

        $insertQuery = $this->getWriter()->addQueryBuilder($insertQueryBuilder);
        $this->getWriter()->getResultList();

        $insertResult = $insertQuery->getResult();
        $this->assertTrue($insertResult->isSuccessfully(), 'Fall insertQuery is not successfully done.');
        $this->assertTablesHSEqual(__METHOD__);
    }

    public function testBuilderMultiInsert()
    {
        $insertQueryBuilder = QueryBuilder::insert();
        $insertQueryBuilder->toDatabase($this->getDatabase())->toTable(
            $this->getTableName()
        )->addRowList(
            array(
                array(
                    'key' => '123',
                    'date' => '0000-00-00',
                    'float' => '1.02',
                    'varchar' => 'char',
                    'text' => 'text',
                    'set' => 'a',
                    'union' => 'a',
                    // TODO fix bug with 'null' => null
                ),
                array(
                    'key' => '124',
                    'date' => '0000-00-00',
                    'float' => '1.04',
                    'varchar' => 'char',
                    'text' => 'text',
                    'set' => 'a',
                    'union' => 'a',
                    // TODO fix bug with 'null' => null
                )
            )
        );

        $insertQuery = $this->getWriter()->addQueryBuilder($insertQueryBuilder);
        $this->getWriter()->getResultList();

        $insertResult = $insertQuery->getResult();
        $this->assertTrue($insertResult->isSuccessfully(), 'Fall insertQuery is not successfully done.');
        $this->assertTablesHSEqual(__METHOD__);
    }
} 