<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Component;

use HS\Component\Comparison;
use HS\Exception\ComparisonException;
use PHPUnit_Framework_TestCase;

class ComparisonTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        foreach (array('=', '>', '>=', '<', '<=') as $comparisonOperation) {
            $comparison = new Comparison($comparisonOperation);
            self::assertEquals($comparisonOperation, $comparison->getComparison(), "Not equal.");
        }
    }

    public function testConstructorException()
    {
        try {
            new Comparison('?');
        } catch (ComparisonException $e) {
            return;
        }

        self::fail("Not fall with wrong comparison parameter.");
    }
} 