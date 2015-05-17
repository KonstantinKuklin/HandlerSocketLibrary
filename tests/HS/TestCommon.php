<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests;

use Exception;
use HS\Reader;
use HS\Result\ResultInterface;
use HS\Writer;
use PHPUnit_Framework_TestCase;

class TestCommon extends PHPUnit_Framework_TestCase
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
    private $reader = null;
    /**
     * @var Writer
     */
    private $writer = null;

    public function __construct()
    {
        if ($this->reader === null) {
            $this->reader = new Reader(self::HOST, self::PORT_RO, $this->getReadPassword());
            $this->reader->close();
        }

        if ($this->writer === null) {
            $this->writer = new Writer(self::HOST, self::PORT_RW, $this->getWritePassword());
            $this->writer->close();
        }
        
        parent::__construct();
    }

    protected function setUp()
    {
        $this->getReader()->open();
        $this->getWriter()->open();

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
        return $this->reader;
    }

    /**
     * @return Writer
     */
    protected function getWriter()
    {
        return $this->writer;
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
            self::fail("Fail because response list is empty.");
        }

        $lastResult = array_pop($resultList);
        if (!($lastResult instanceof ResultInterface)) {
            self::fail("Fail because result is not implemented ResultInterface.");

        }

        // equal actual and expected
        self::assertEquals($expectedData, $lastResult->getData(), $assertMessage);
    }

    /**
     * @param \HS\ReaderInterface $socket
     * @param string              $assertMessage
     * @param int                 $expectedCount
     */
    protected function checkCountRequestSent($socket, $assertMessage, $expectedCount)
    {
        $resultList = $socket->getResultList();
        self::assertEquals($expectedCount, count($resultList), $assertMessage);
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
            self::fail("Fail because response list is empty.");
        }

        $lastResult = array_pop($resultList);
        if ($lastResult->isSuccessfully()) {
            self::fail("Fail because response is successfully finished.");
        }
        $errorObject = $lastResult->getError();
        $errorClass = get_class($errorObject);

        self::assertEquals($expectedError, $errorClass, $assertMessage);
    }
}