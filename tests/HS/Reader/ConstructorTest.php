<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\HSReader;


use HS\Reader;
use HS\Tests\TestCommon;
use Stream\Exceptions\PortValidateStreamException;

class ConstructorTest extends TestCommon
{

    public function testInvalidPortException()
    {
        $portWrong = '-1';

        try {
            $reader = new Reader($this->getHost(), $portWrong);
        } catch (PortValidateStreamException $e) {
            return;
        }

        $this->fail("Reader constructor didn't fall with exception PortValidateStreamException on wrong port set.");
    }

    public function testGoodConnectionToReadPort()
    {
        $portGood = 9998;

        try {
            new Reader($this->getHost(), $portGood);
        } catch (\Exception $e) {
            $this->fail(
                sprintf(
                    "Fall with valid parameters to constructor. Host:%s, port:%s",
                    $this->getHost(),
                    $portGood
                )
            );
        }
        $this->assertTrue(true);
    }

    public function testAuthRequestAdded()
    {
        $portGood = 9999;
        $pass = 'testpass';

        $reader = new Reader($this->getHost(), $portGood, $pass);
        $this->assertEquals(1, $reader->getCountQueriesInQueue(), "Auth request not added on init hs reader.");
        $reader->getResults();
    }

    public function testAuthRequestNotAdded()
    {
        $portGood = 9999;

        $reader = new Reader($this->getHost(), $portGood);
        $this->assertEquals(0, $reader->getCountQueriesInQueue(), "Auth request added on init hs reader.");
        $reader->getResults();
    }
} 