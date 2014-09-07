<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Tests\Builder;

use HS\Component\Comparison;
use HS\QueryBuilder;
use HS\Tests\TestCommon;

class UpdateQueryBuilderTest extends TestCommon
{
    public function testSingleUpdate()
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
        $selectQuery = $this->getWriter()->selectByIndex($updateQuery->getIndexId(), Comparison::EQUAL, array('2'));

        $this->getWriter()->getResultList();

        $updateResult = $updateQuery->getResult();
        $this->assertTrue($updateResult->isSuccessfully(), 'Fall updateQuery is not successfully done.');


        $this->assertEquals(
            array(
                array(
                    'key' => '2',
                    'varchar' => 'test update query'
                )
            ),
            $selectQuery->getResult()->getData(),
            'Fall returned data not valid.'
        );
    }
} 