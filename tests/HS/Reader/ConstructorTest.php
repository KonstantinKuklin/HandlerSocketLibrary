<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Reader;

use HS\Errors\AuthenticationError;
use HS\Reader;
use HS\Tests\TestCommon;
use Stream\Exception\PortValidateStreamException;

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

        self::fail("Reader constructor didn't fall with exception PortValidateStreamException on wrong port set.");
    }

    public function testGoodConnectionToReadPort()
    {
        $portGood = 9998;

        $reader = null;
        try {
            $reader = new Reader($this->getHost(), $portGood);
        } catch (\Exception $e) {
            self::fail(
                sprintf(
                    "Fall with valid parameters to constructor. Host:%s, port:%s",
                    $this->getHost(),
                    $portGood
                )
            );
        }
        $reader->close();
    }

    public function testAuthRequestAdded()
    {
        $portGood = 9999;
        $pass = 'testpass';
        try {
            $reader = new Reader($this->getHost(), $portGood, $pass);
            self::assertEquals(1, $reader->getCountQueriesInQueue(), "Auth request not added on init hs reader.");
            $reader->getResultList();
        } catch (AuthenticationError $e) {
            return;
        }
        self::fail("Not fall without auth.");
    }

    public function testAuthRequestNotAdded()
    {
        $portGood = 9999;
        $reader = new Reader($this->getHost(), $portGood);
        self::assertEquals(0, $reader->getCountQueriesInQueue(), "Auth request added on init hs reader.");
        $reader->getResultList();
        $reader->close();
    }
} 