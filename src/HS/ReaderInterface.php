<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS;

use HS\Builder\QueryBuilderInterface;
use HS\Query\ModifyQueryAbstract;

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
     * @throws \Stream\Exception\StreamException
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
     * @throws \Stream\Exception\StreamException
     */
    public function sendQueries();

    /**
     * @throws \Stream\Exception\StreamException
     * @return void
     */
    public function reOpen();

    /**
     * @param QueryBuilderInterface $queryBuilder
     *
     * @return QueryInterface|ModifyQueryAbstract
     */
    public function addQueryBuilder(QueryBuilderInterface $queryBuilder);

    /**
     * @param QueryInterface $query
     *
     * @return void
     * @throws \Exception
     */
    public function addQuery(QueryInterface $query);
}