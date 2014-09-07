<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Component;

use HS\Component\ParameterBag;
use PHPUnit_Framework_TestCase;

class ParameterBagTest extends PHPUnit_Framework_TestCase
{

    private $bag = null;

    public function __construct()
    {
        $this->bag = new ParameterBag(array("int" => 50, "text" => 'simple text'));
    }

    public function testGetParameter()
    {
        $this->assertEquals(50, $this->bag->getParameter('int'), 'Wrong parameter');
    }

    public function testGetDefaultParameter()
    {
        $this->assertEquals(null, $this->bag->getParameter('none'), 'Wrong parameter');
    }

    public function testGetDefaultUserParameter()
    {
        $this->assertEquals('default', $this->bag->getParameter('none', 'default'), 'Wrong parameter');
    }

    public function testSetParameter()
    {
        $this->bag->setParameter('none2', 'default2');
        $this->assertEquals('default2', $this->bag->getParameter('none2'), 'Wrong parameter');
    }

    public function testIsExists()
    {
        $this->assertTrue($this->bag->isExists('int'), 'Wrong parameter');
        $this->assertFalse($this->bag->isExists('int_none'), 'Wrong parameter');
    }

    public function testIsExistsEmpty()
    {
        $this->bag->setParameter('empty', '');
        $this->assertTrue($this->bag->isExists('empty'), 'Wrong parameter');
    }

    public function testAddRowToParameter()
    {
        $this->bag->addRowToParameter("array", 'list');
        $this->assertEquals(array('list'), $this->bag->getParameter('array'));
    }

    public function testGetAsArray()
    {
        $this->assertEquals(array('int' => 50, 'text' => 'simple text'), $this->bag->getAsArray());
    }
} 