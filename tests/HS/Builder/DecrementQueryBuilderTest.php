<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Builder;

use HS\Component\Comparison;
use HS\Exception\InvalidArgumentException;
use HS\QueryBuilder;
use HS\Tests\TestWriterCommon;

class DecrementQueryBuilderTest extends TestWriterCommon
{
    public function testBuilderSingleDecrement()
    {
        $decrementQueryBuilder = QueryBuilder::decrement(array('key' => 0, 'num'))
            ->fromDataBase($this->getDatabase())
            ->fromTable($this->getTableName())
            ->where(Comparison::EQUAL, array('key' => 105));

        $decrementQuery = $this->getWriter()->addQueryBuilder($decrementQueryBuilder);

        $this->getWriter()->getResultList();

        $updateResult = $decrementQuery->getResult();
        self::assertTrue($updateResult->isSuccessfully(), 'Fall decrementQuery is not successfully done.');
        self::assertTablesHSEqual(__METHOD__);
    }

    public function testBuilderDecrementException()
    {
        try {
            QueryBuilder::decrement(array('key' => 0, 'num' => 'text'))
                ->fromDataBase($this->getDatabase())
                ->fromTable($this->getTableName())
                ->where(Comparison::EQUAL, array('key' => 105));
        } catch (InvalidArgumentException $e) {
            return;
        }
        self::fail('Not fall decrementBuilder with wrong parameters.');
    }
} 