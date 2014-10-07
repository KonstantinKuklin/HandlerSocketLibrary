<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Builder;

use HS\Component\Comparison;
use HS\QueryBuilder;
use HS\Tests\TestWriterCommon;

class UpdateQueryBuilderTest extends TestWriterCommon
{
    public function testBuilderSingleUpdate()
    {
        $updateQueryBuilder = QueryBuilder::update(
            array(
                'key' => 2,
                'varchar' => 'test update query'
            )
        )
            ->fromDataBase($this->getDatabase())
            ->fromTable($this->getTableName())
            ->where(Comparison::EQUAL, array('key' => 2));

        $updateQuery = $this->getWriter()->addQueryBuilder($updateQueryBuilder);

        $this->getWriter()->getResultList();

        $updateResult = $updateQuery->getResult();
        $this->assertTrue($updateResult->isSuccessfully(), 'Fall updateQuery is not successfully done.');
        $this->assertTablesHSEqual(__METHOD__);
    }

    public function testBuilderSingleUpdateSuffix()
    {
        $updateQueryBuilder = QueryBuilder::update(
            array(
                'key' => 3,
                'varchar' => 'test again update query'
            )
        )
            ->fromDataBase($this->getDatabase())
            ->fromTable($this->getTableName())
            ->where(Comparison::EQUAL, array('key' => 3))->withSuffix();

        $updateQuery = $this->getWriter()->addQueryBuilder($updateQueryBuilder);
        $this->getWriter()->getResultList();

        $updateResult = $updateQuery->getResult();
        $this->assertTrue($updateResult->isSuccessfully(), 'Fall updateQuery is not successfully done.');


        $this->assertEquals(
            array(
                array(
                    'key' => '3',
                    'varchar' => ''
                )
            ),
            $updateQuery->getResult()->getData(),
            'Fall returned data not valid.'
        );
        $this->assertTablesHSEqual(__METHOD__);
    }
} 