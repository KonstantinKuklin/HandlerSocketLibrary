<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests;

use HS\Reader;
use HS\Writer;
use PHPUnit_Extensions_Database_DataSet_IDataSet;
use PHPUnit_Extensions_Database_DB_IDatabaseConnection;

class TestWriterCommon extends \PHPUnit_Extensions_Database_TestCase
{
    // only instantiate pdo once for test clean-up/fixture load
    static private $pdo = null;

    // only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
    private $conn = null;
    private static $dataset = null;

    public function __construct()
    {
        if (TestCommon::$reader === null) {
            TestCommon::$reader = new Reader(TestCommon::HOST, TestCommon::PORT_RO, TestCommon::READ_PASSWORD);
        }

        if (TestCommon::$writer === null) {
            TestCommon::$writer = new Writer(TestCommon::HOST, TestCommon::PORT_RW, TestCommon::WRITE_PASSWORD);
        }
    }

    protected function setUp()
    {
        $this->getWriter()->open();
        $this->getReader()->open();
        parent::setUp();
    }

    protected function tearDown()
    {
        $this->getWriter()->close();
        $this->getReader()->close();
        parent::tearDown();
    }

    /**
     * Returns the test database connection.
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new \PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD']);
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }

    /**
     * Returns the test dataset.
     *
     * @throws \Exception
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        if (self::$dataset === null) {
            $yamlFixturesFile = __DIR__ . $GLOBALS['YAML_FILE'];
            if (!file_exists($yamlFixturesFile)) {
                throw new \Exception("THe path to yaml fixtures file is wrong.");
            }
            self::$dataset = new \PHPUnit_Extensions_Database_DataSet_YamlDataSet($yamlFixturesFile);
        }

        return self::$dataset;
    }

    /**
     * @return Reader
     */
    protected function getReader()
    {
        return TestCommon::$reader;
    }

    /**
     * @return Writer
     */
    protected function getWriter()
    {
        return TestCommon::$writer;
    }

    /**
     * @return string
     */
    protected function getDatabase()
    {
        return TestCommon::DATABASE;
    }

    /**
     * @return string
     */
    protected function getTableName()
    {
        return TestCommon::TABLE;
    }

    protected function getActualTable()
    {
        return $this->getConnection()->createQueryTable('hs', 'SELECT * FROM hs;');
    }

    public function assertTablesHSEqual($methodName)
    {
        $list = explode('::', $methodName);
        $methodName = $list[1];

        $filePath = __DIR__ .'/../resources/fixture/'. $methodName . 'Fixture.yml';
        if (!file_exists($filePath)) {
            throw new \Exception(sprintf("File '%s' not exists.", $filePath));
        }

        $yamlFixtures = new \PHPUnit_Extensions_Database_DataSet_YamlDataSet($filePath);

        return parent::assertTablesEqual($yamlFixtures->getTable($this->getTableName()), $this->getActualTable());
    }
}