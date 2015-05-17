<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Builder;

use HS\Component\Comparison;
use HS\QueryBuilder;
use HS\Tests\TestWriterCommon;

class DeleteQueryBuilderTest extends TestWriterCommon
{
    public function testBuilderSingleDelete()
    {
        $deleteQueryBuilder = QueryBuilder::delete();
        $deleteQueryBuilder->fromDataBase($this->getDatabase())->fromTable(
            $this->getTableName()
        )->where(Comparison::EQUAL, array('key' => 5));

        $deleteQuery = $this->getWriter()->addQueryBuilder($deleteQueryBuilder);
        $this->getWriter()->getResultList();

        $updateResult = $deleteQuery->getResult();
        self::assertTrue($updateResult->isSuccessfully(), 'Fall deleteQuery is not successfully done.');
        self::assertTablesHSEqual(__METHOD__);
    }
}