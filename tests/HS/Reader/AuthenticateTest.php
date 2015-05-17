<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Reader;

use HS\Exception\InvalidArgumentException;
use HS\Tests\TestCommon;
use Stream\Stream;

class AuthenticateTest extends TestCommon
{

    public function testInt()
    {
        $reader = $this->getReader();

        try {
            $reader->authenticate(90);
        } catch (InvalidArgumentException $e) {
            return;
        }

        self::fail("Not fail authentication request with wrong auth key, sent int.");
    }

    public function testEmptyString()
    {
        $reader = $this->getReader();

        try {
            $reader->authenticate(Stream::STR_EMPTY);
        } catch (InvalidArgumentException $e) {
            return;
        }

        self::fail("Not fail authentication request with wrong auth key, sent empty string.");
    }

    public function testValidMissedStringToAuth()
    {
        $reader = $this->getReader();
        $authKey = "text";
        try {
            $reader->authenticate($authKey);
        } catch (InvalidArgumentException $e) {
            self::fail(sprintf("Fail authentication request with valid parameter, sent string:%s.", $authKey));
        }

        return;
    }
}