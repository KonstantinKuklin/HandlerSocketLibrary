<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests;

use HS\Reader;
use HS\Result\ResultInterface;
use HS\Writer;

class TestCommon extends \PHPUnit_Framework_TestCase
{
    const HOST = '127.0.0.1';
    const PORT_RO = 9998;
    const PORT_RW = 9999;
    const DATABASE = 'handlersocket';
    const TABLE = 'hs';

    const READ_PASSWORD = 'Password_Read1';
    const WRITE_PASSWORD = 'Password_Write1';

    /**
     * @var Reader
     */
    public static $reader = null;
    /**
     * @var Writer
     */
    public static $writer = null;

    public function __construct()
    {
        if (self::$reader === null) {
            self::$reader = new Reader(self::HOST, self::PORT_RO, $this->getReadPassword());
        }

        if (self::$writer === null) {
            self::$writer = new Writer(self::HOST, self::PORT_RW, $this->getWritePassword());
        }
    }

    protected function setUp(){
        try {
            $this->getWriter()->open();
            $this->getReader()->open();
        } catch (\Exception $e){
            echo ""; // setUp after construct will provide already opened exception
        }
        parent::setUp();
    }

    protected function tearDown()
    {
        $this->getWriter()->close();
        $this->getReader()->close();
        parent::tearDown();
    }

    /**
     * @return string
     */
    protected function getHost()
    {
        return self::HOST;
    }

    /**
     * @return string
     */
    protected function getTableName()
    {
        return self::TABLE;
    }

    /**
     * @return Reader
     */
    protected function getReader()
    {
        return self::$reader;
    }

    /**
     * @return Writer
     */
    protected function getWriter()
    {
        return self::$writer;
    }

    /**
     * @return string
     */
    protected function getDatabase()
    {
        return self::DATABASE;
    }

    /**
     * @return string
     */
    protected function getReadPassword()
    {
        return self::READ_PASSWORD;
    }

    /**
     * @return string
     */
    protected function getWritePassword()
    {
        return self::WRITE_PASSWORD;
    }

    /**
     * @param \HS\ReaderInterface $socket
     * @param string              $assertMessage
     * @param array               $expectedData
     */
    protected function checkAssertionLastResponseData(
        $socket, $assertMessage, $expectedData
    ) {
        $resultList = $socket->getResultList();
        if (empty($resultList)) {
            $this->fail("Fail because response list is empty.");
        }

        $lastResult = array_pop($resultList);
        if (!($lastResult instanceof ResultInterface)) {
            $this->fail("Fail because result is not implemented ResultInterface.");

        }

        // equal actual and expected
        $this->assertEquals($expectedData, $lastResult->getData(), $assertMessage);
    }

    /**
     * @param \HS\ReaderInterface $socket
     * @param string              $assertMessage
     * @param int                 $expectedCount
     */
    protected function checkCountRequestSent($socket, $assertMessage, $expectedCount)
    {
        $resultList = $socket->getResultList();
        $this->assertEquals($expectedCount, count($resultList), $assertMessage);
    }

    /**
     * @param \HS\ReaderInterface $socket
     * @param string              $assertMessage
     * @param string              $expectedError
     */
    protected function checkError($socket, $assertMessage, $expectedError)
    {
        $resultList = $socket->getResultList();
        if (empty($resultList)) {
            $this->fail("Fail because response list is empty.");
        }

        $lastResult = array_pop($resultList);
        if ($lastResult->isSuccessfully()) {
            $this->fail("Fail because response is successfully finished.");
        }
        $errorObject = $lastResult->getError();
        $errorClass = get_class($errorObject);

        $this->assertEquals($expectedError, $errorClass, $assertMessage);
    }
}