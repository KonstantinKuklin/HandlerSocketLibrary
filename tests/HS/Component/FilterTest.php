<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Component;

use HS\Component\Comparison;
use HS\Component\Filter;
use PHPUnit_Framework_TestCase;

class FilterTest extends PHPUnit_Framework_TestCase
{
    private $filter = null;

    public function __construct()
    {
        $this->filter = new Filter(Comparison::EQUAL, 5, 'key');
        parent::__construct();
    }

    public function testGetComparison()
    {
        self::assertEquals(Comparison::EQUAL, $this->filter->getComparison(), 'Fail returned comparison is wrong.');
    }

    public function testGetComparisonObject()
    {
        self::assertTrue(
            $this->filter->getComparisonObject() instanceof Comparison,
            'Fail returned comparisonObject is wrong.'
        );
    }

    public function testGetPosition()
    {
        self::assertEquals(5, $this->filter->getPosition(), 'Fail returned position is wrong.');
    }

    public function testGetKey()
    {
        self::assertEquals('key', $this->filter->getKey(), 'Fail returned key is wrong.');
    }

    public function testGetDefaultType()
    {
        self::assertEquals('F', $this->filter->getType(), 'Fail returned type is wrong.');
    }

    public function testConstructWithComparisonClass()
    {
        $filter = new Filter(new Comparison(Comparison::EQUAL), 5, 'key');
    }
} 