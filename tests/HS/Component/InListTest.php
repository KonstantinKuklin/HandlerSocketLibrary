<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Component;

use HS\Component\InList;
use HS\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase;

class InListTest extends PHPUnit_Framework_TestCase
{
    private $inList = null;

    public function __construct()
    {
        $this->inList = new InList(0, array('value1', 'value2'));
    }

    public function testGetPosition()
    {
        $this->assertEquals(0, $this->inList->getPosition());
    }

    public function testGetCount()
    {
        $this->assertEquals(2, $this->inList->getCount());
    }

    public function testGetKeyList()
    {
        $this->assertEquals(array('value1', 'value2'), $this->inList->getKeyList());
    }

    public function testConstructorException()
    {
        try {
            new InList('s', array());
        } catch (InvalidArgumentException $e) {
            return true;
        }

        $this->fail("Not fall with string as position.");
    }
} 