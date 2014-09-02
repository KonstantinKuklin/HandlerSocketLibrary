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
        $updateQueryBuilder = QueryBuilder::update(
            array(
                'key' => 2,
                'varchar' => 'test update query'
            )
        );
        $updateQueryBuilder->fromDataBase($this->getDatabase())->fromTable(
            $this->getTableName()
        )->where(HSInterface::EQUAL, array('key' => 2));

        $updateQuery = $this->getWriter()->addQueryBuilder($updateQueryBuilder);
        $selectQuery = $this->getWriter()->selectByIndex($updateQuery->getIndexId(), HSInterface::EQUAL, array('2'));

        $this->getWriter()->getResults();

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