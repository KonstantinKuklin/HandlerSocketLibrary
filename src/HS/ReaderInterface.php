<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS;

use HS\Builder\QueryBuilderInterface;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
interface ReaderInterface extends ReaderHSInterface
{
    /**
     * @return boolean
     */
    public function isDebug();

    /**
     * @return ResultInterface[]
     * @throws \Stream\Exceptions\StreamException
     */
    public function getResults();

    /**
     * @return int
     */
    public function getCountQueriesInQueue();

    /**
     * @return int
     */
    public function getCountQueries();

    /**
     * @return double
     */
    public function getTimeQueries();

    /**
     * @return string
     */
    public function getUrlConnection();

    /**
     * @throws \Stream\Exceptions\StreamException
     */
    public function sendQueries();

    /**
     * @throws \Stream\Exceptions\StreamException
     * @return void
     */
    public function reOpen();

    /**
     * @param QueryBuilderInterface|QueryInterface $query
     *
     * @return QueryInterface|void
     * @throws \Exception
     */
    public function addQuery($query);
}