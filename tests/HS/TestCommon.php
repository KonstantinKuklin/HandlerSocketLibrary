<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests;


use HS\Reader;
use HS\Writer;
use HS\ResponseInterface;

class TestCommon extends \PHPUnit_Framework_TestCase
{

    const IP = '127.0.0.1';
    const PORT_RO = 9998;
    const PORT_RW = 9999;
    const DATABASE = 'hs';

    const READ_PASSWORD = 'Password_Read1';
    const WRITE_PASSWORD = 'Password_Write1';

    const SQL_FILE = '../tests/resources/preTests.sql';

    /**
     * @var null|Reader
     */
    protected static $hsReader = null;
    /**
     * @var null|Writer
     */
    protected static $hsWriter = null;

    public function __construct()
    {
        if (self::$hsReader === null) {
            self::$hsReader = new Reader(self::IP, self::PORT_RO, $this->getReadPassword());
        }

        if (self::$hsWriter === null) {
            self::$hsWriter = new Writer(self::IP, self::PORT_RW, $this->getWritePassword());
        }
    }

    protected function getSqlFilePath()
    {
        return self::SQL_FILE;
    }

    /**
     * @return Reader
     */
    protected function getReader()
    {
        return self::$hsReader;
    }

    protected function getWriter()
    {
        return self::$hsWriter;
    }

    /**
     * @return string
     */
    protected function getDatabase()
    {
        return self::DATABASE;
    }

    protected function getReadPassword()
    {
        return self::READ_PASSWORD;
    }

    protected function getWritePassword()
    {
        return self::WRITE_PASSWORD;
    }

    protected function splitSQL($file, $delimiter = ';')
    {
        set_time_limit(0);

        if (is_file($file) === true) {
            $file = fopen($file, 'r');
            if (is_resource($file) === true) {
                $query = array();

                while (feof($file) === false) {
                    $query[] = fgets($file);

                    if (preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1) {
                        $query = trim(implode('', $query));

                        if (mysql_query($query) === false) {
                            echo 'ERROR: ' . $query . "\n";
                        } else {
                            echo 'SUCCESS: ' . $query . "\n";
                        }

                        while (ob_get_level() > 0) {
                            ob_end_flush();
                        }
                        flush();
                    }
                    if (is_string($query) === true) {
                        $query = array();
                    }
                }

                return fclose($file);
            }
        }

        return false;
    }

    /**
     * @param \HS\ReaderInterface $socket
     * @param string              $assertMessage
     * @param array               $expectedData
     */
    protected function checkAssertionLastResponseData(
        $socket, $assertMessage, $expectedData
    ) {
        $responseList = $socket->getResponses();
        if (empty($responseList)) {
            $this->fail("Fail because response list is empty.");
        }

        $lastResponse = array_pop($responseList);
        if (!($lastResponse instanceof ResponseInterface)) {
            $this->fail("Fail because response is not implemented ResponseInterface.");

        }

        // equal actual and expected
        $this->assertEquals($expectedData, $lastResponse->getData(), $assertMessage);
    }

    /**
     * @param \HS\ReaderInterface $socket
     * @param string              $assertMessage
     * @param int                 $expectedCount
     */
    protected function checkCountRequestSended($socket, $assertMessage, $expectedCount)
    {
        $responseList = $socket->getResponses();
        $this->assertEquals($expectedCount, count($responseList), $assertMessage);
    }

    /**
     * @param \HS\ReaderInterface $socket
     * @param string              $assertMessage
     * @param string              $expectedError
     */
    protected function checkError($socket, $assertMessage, $expectedError)
    {
        $responseList = $socket->getResponses();
        if (empty($responseList)) {
            $this->fail("Fail because response list is empty.");
        }

        $lastResponse = array_pop($responseList);
        if ($lastResponse->isSuccessfully()) {
            $this->fail("Fail because response is successfully finished.");
        }
        $errorObject = $lastResponse->getError();
        $errorClass = get_class($errorObject);

        $this->assertEquals($expectedError, $errorClass, $assertMessage);
    }
}