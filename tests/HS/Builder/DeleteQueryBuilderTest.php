<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Tests\Builder;

use HS\Component\Comparison;
use HS\QueryBuilder;
use HS\Tests\TestCommon;

class DeleteQueryBuilderTest extends TestCommon
{
    public function testSingleDelete()
    {
        $deleteQueryBuilder = QueryBuilder::delete();
        $deleteQueryBuilder->fromDataBase($this->getDatabase())->fromTable(
            $this->getTableName()
        )->where(Comparison::EQUAL, array('key' => 5));

        $deleteQuery = $this->getWriter()->addQueryBuilder($deleteQueryBuilder);
        $selectQuery = $this->getWriter()->selectByIndex($deleteQuery->getIndexId(), Comparison::EQUAL, array('5'));

        $this->getWriter()->getResultList();

        $updateResult = $deleteQuery->getResult();
        $this->assertTrue($updateResult->isSuccessfully(), 'Fall deleteQuery is not successfully done.');

        $data = $selectQuery->getResult()->getData();
        $this->assertTrue(empty($data), 'Fall returned data not valid.');
    }
} 