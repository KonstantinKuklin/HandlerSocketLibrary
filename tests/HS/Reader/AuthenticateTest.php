<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\HSReader;

use HS\Exceptions\WrongParameterException;
use HS\Tests\TestCommon;
use Stream\Stream;

class AuthenticateTest extends TestCommon
{

    public function testInt()
    {
        $reader = $this->getReader();

        try {
            $reader->authenticate(90);
        } catch (WrongParameterException $e) {
            return;
        }

        $this->fail("Not fail authentication request with bad parameter, sent int.");
    }

    public function testEmptyString()
    {
        $reader = $this->getReader();

        try {
            $reader->authenticate(Stream::STR_EMPTY);
        } catch (WrongParameterException $e) {
            return;
        }

        $this->fail("Not fail authentication request with bad parameter, sent empty string.");
    }

    public function testValid()
    {
        $reader = $this->getReader();
        $authKey = "text";
        try {
            $reader->authenticate($authKey);
        } catch (WrongParameterException $e) {
            $this->fail(sprintf("Fail authentication request with valid parameter, sent string:%s.", $authKey));
        }

        return;
    }
}