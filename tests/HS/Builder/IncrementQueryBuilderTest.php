<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Builder;

use HS\Component\Comparison;
use HS\Exception\WrongParameterException;
use HS\QueryBuilder;
use HS\Tests\TestCommon;

class IncrementQueryBuilderTest extends TestCommon
{
    public function testSingleIncrement()
    {
        $incrementQueryBuilder = QueryBuilder::increment(array('key' => 0, 'num'))
            ->fromDataBase($this->getDatabase())
            ->fromTable($this->getTableName())
            ->where(Comparison::EQUAL, array('key' => 104));

        $incrementQuery = $this->getWriter()->addQueryBuilder($incrementQueryBuilder);
        $selectQuery = $this->getWriter()->selectByIndex($incrementQuery->getIndexId(), Comparison::EQUAL, array('104'));

        $this->getWriter()->getResultList();

        $incrementResult = $incrementQuery->getResult();
        $this->assertTrue($incrementResult->isSuccessfully(), 'Fall incrementQuery is not successfully done.');


        $this->assertEquals(
            array(
                array(
                    'key' => '104',
                    'num' => '11'
                )
            ),
            $selectQuery->getResult()->getData(),
            'Fall returned data not valid.'
        );
    }

    public function testIncrementException()
    {
        try {
            QueryBuilder::increment(array('key' => 0, 'num' => 'text'))
                ->fromDataBase($this->getDatabase())
                ->fromTable($this->getTableName())
                ->where(Comparison::EQUAL, array('key' => 104));
        } catch (WrongParameterException $e) {
            return true;
        }
        $this->fail('Not fall incrementBuilder with wrong parameters.');

    }
} 