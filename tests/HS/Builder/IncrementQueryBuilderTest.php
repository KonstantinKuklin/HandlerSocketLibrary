<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Builder;

use HS\Component\Comparison;
use HS\Exception\InvalidArgumentException;
use HS\QueryBuilder;
use HS\Tests\TestWriterCommon;

class IncrementQueryBuilderTest extends TestWriterCommon
{
    public function testBuilderSingleIncrement()
    {
        $incrementQueryBuilder = QueryBuilder::increment(array('key' => 0, 'num' => 5))
            ->fromDataBase($this->getDatabase())
            ->fromTable($this->getTableName())
            ->where(Comparison::EQUAL, array('key' => 104));

        $incrementQuery = $this->getWriter()->addQueryBuilder($incrementQueryBuilder);

        $this->getWriter()->getResultList();

        $incrementResult = $incrementQuery->getResult();
        $this->assertTrue($incrementResult->isSuccessfully(), 'Fall incrementQuery is not successfully done.');
        $this->assertTablesHSEqual(__METHOD__);
    }

    public function testBuilderIncrementException()
    {
        try {
            QueryBuilder::increment(array('key' => 0, 'num' => 'text'))
                ->fromDataBase($this->getDatabase())
                ->fromTable($this->getTableName())
                ->where(Comparison::EQUAL, array('key' => 104));
        } catch (InvalidArgumentException $e) {
            return true;
        }
        $this->fail('Not fall incrementBuilder with wrong parameters.');
    }

    // limit value increase num ?? TODO
    public function testBugSingleIncrement()
    {
        $incrementQueryBuilder = QueryBuilder::increment(array('key' => 0, 'num'))
            ->fromDataBase($this->getDatabase())
            ->fromTable($this->getTableName())
            ->where(Comparison::EQUAL, array('key' => 104))
            ->limit(100);

        $incrementQuery = $this->getWriter()->addQueryBuilder($incrementQueryBuilder);

        $this->getWriter()->getResultList();

        $incrementResult = $incrementQuery->getResult();
        $this->assertTrue($incrementResult->isSuccessfully(), 'Fall incrementQuery is not successfully done.');
        $this->assertTablesHSEqual(__METHOD__);
    }
}