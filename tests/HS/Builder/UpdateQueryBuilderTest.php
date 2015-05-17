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
        self::assertTrue($updateResult->isSuccessfully(), 'Fall updateQuery is not successfully done.');
        self::assertTablesHSEqual(__METHOD__);
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
        self::assertTrue($updateResult->isSuccessfully(), 'Fall updateQuery is not successfully done.');


        self::assertEquals(
            array(
                array(
                    'key' => '3',
                    'varchar' => ''
                )
            ),
            $updateQuery->getResult()->getData(),
            'Fall returned data not valid.'
        );
        self::assertTablesHSEqual(__METHOD__);
    }

    public function testBuilderSingleUpdateSuffixWithNullValue()
    {
        $updateQueryBuilder = QueryBuilder::update(
            array(
                'key' => 200,
                'num' => null
            )
        )
            ->fromDataBase($this->getDatabase())
            ->fromTable($this->getTableName())
            ->where(Comparison::EQUAL, array('key' => 3))->withSuffix();

        $updateQuery = $this->getWriter()->addQueryBuilder($updateQueryBuilder);
        $this->getWriter()->getResultList();

        $updateResult = $updateQuery->getResult();
        self::assertTrue($updateResult->isSuccessfully(), 'Fall updateQuery is not successfully done.');


        $dataUpdated = $updateQuery->getResult()->getData();
        self::assertEquals(
            array(
                array(
                    'key' => '3',
                    'num' => 0
                )
            ),
            $dataUpdated,
            'Fall returned data not valid.'
        );

        $selectQb = QueryBuilder::select(array('key', 'num', 'null'))
            ->fromDataBase($this->getDatabase())
            ->fromTable($this->getTableName())
            ->where(Comparison::EQUAL, array('key' => 200))->withSuffix();

        $selectQuery = $this->getReader()->addQueryBuilder($selectQb);

        $this->getReader()->getResultList();

        self::assertEquals(
            array(array('key' => 200, 'num' => null, 'null' => null)),
            $selectQuery->getResult()->getData(),
            'Fail with wrong data in Db'
        );

        self::assertTablesHSEqual(__METHOD__);
    }
} 