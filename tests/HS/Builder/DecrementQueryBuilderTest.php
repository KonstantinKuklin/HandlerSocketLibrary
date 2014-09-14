<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Builder;

use HS\Component\Comparison;
use HS\Exception\WrongParameterException;
use HS\QueryBuilder;
use HS\Tests\TestCommon;

class DecrementQueryBuilderTest extends TestCommon
{
    public function testSingleDecrement()
    {
        $decrementQueryBuilder = QueryBuilder::decrement(array('key' => 0, 'num'))
            ->fromDataBase($this->getDatabase())
            ->fromTable($this->getTableName())
            ->where(Comparison::EQUAL, array('key' => 105));

        $decrementQuery = $this->getWriter()->addQueryBuilder($decrementQueryBuilder);
        $selectQuery = $this->getWriter()->selectByIndex($decrementQuery->getIndexId(), Comparison::EQUAL, array('105'));

        $this->getWriter()->getResultList();

        $updateResult = $decrementQuery->getResult();
        $this->assertTrue($updateResult->isSuccessfully(), 'Fall decrementQuery is not successfully done.');


        $this->assertEquals(
            array(
                array(
                    'key' => '105',
                    'num' => '9'
                )
            ),
            $selectQuery->getResult()->getData(),
            'Fall returned data not valid.'
        );
    }

    public function testDecrementException()
    {
        try {
            QueryBuilder::decrement(array('key' => 0, 'num' => 'text'))
                ->fromDataBase($this->getDatabase())
                ->fromTable($this->getTableName())
                ->where(Comparison::EQUAL, array('key' => 105));
        } catch (WrongParameterException $e) {
            return true;
        }
        $this->fail('Not fall decrementBuilder with wrong parameters.');

    }
} 