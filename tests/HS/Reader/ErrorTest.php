<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Reader;

use HS\Error;
use HS\Result\ResultAbstract;
use HS\Tests\TestCommon;

class ResultTest extends ResultAbstract
{

}

class ErrorTest extends TestCommon
{

    private $errorMapList = array(
        'cmd' => 'CommandError',
        'syntax' => 'CommandError',
        'notimpl' => 'CommandError',
        'authtype' => 'AuthenticationError',
        'unauth' => 'AuthenticationError',
        'open_table' => 'OpenTableError',
        'tblnum' => 'IndexOverFlowError',
        'stmtnum' => 'IndexOverFlowError',
        'invalueslen' => 'InListSizeError',
        'filtertype' => 'FilterTypeError',
        'filterfld' => 'FilterColumnError',
        'lock_tables' => 'LockTableError',
        'modop' => 'LockTableError',
        'idxnum' => 'KeyIndexError',
        'kpnum' => 'KeyLengthError',
        'klen' => 'KeyLengthError',
        'op' => 'ComparisonOperatorError',
        'readonly' => 'ReadOnlyError',
        'fld' => 'ColumnParseError',
        '121' => 'InternalMysqlError',
        'filterblob' => 'UnknownError',
        'somerandomError' => 'UnknownError',
    );

    public function testErrors()
    {
        $queryTest = $this->getReader()->text("test", "test");

        foreach ($this->errorMapList as $cmd => $error) {
            $data = array(1, 2, $cmd, 'simple data');
            try {
                $result = new ResultTest($queryTest, $data);
            } catch (Error $e) {
                $actualError = get_class($e);
                $this->assertEquals(
                    'HS\Errors\\' . $error,
                    $actualError,
                    sprintf(
                        'Returned wrong error class on error %s. Must be %s, but got %s.',
                        $cmd,
                        $error,
                        $actualError
                    )
                );

                continue;
            }

            $this->fail('Fail, error won"t catched.');

        }
    }

}