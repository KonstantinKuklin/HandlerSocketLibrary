<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Component;

use HS\Component\Comparison;
use HS\Component\Filter;
use HS\Exception\ComparisonException;
use PHPUnit_Framework_TestCase;

class FilterTest extends PHPUnit_Framework_TestCase
{
    private $filter = null;

    public function __construct()
    {
        $this->filter = new Filter(Comparison::EQUAL, 5, 'key');
    }

    public function testGetComparison()
    {
        $this->assertEquals(Comparison::EQUAL, $this->filter->getComparison(), 'Fail returned comparison is wrong.');
    }

    public function testGetPosition()
    {
        $this->assertEquals(5, $this->filter->getPosition(), 'Fail returned position is wrong.');
    }

    public function testGetKey()
    {
        $this->assertEquals('key', $this->filter->getKey(), 'Fail returned key is wrong.');
    }

    public function testGetDefaultType()
    {
        $this->assertEquals('F', $this->filter->getType(), 'Fail returned type is wrong.');
    }
} 