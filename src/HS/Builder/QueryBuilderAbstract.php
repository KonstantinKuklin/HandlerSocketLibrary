<?php
namespace HS\Builder;

use HS\Component\ParameterBag;
use HS\Query\QueryInterface;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
abstract class QueryBuilderAbstract implements QueryBuilderInterface
{
    private $parameterBag = null;

    /**
     * @return string
     */
    abstract public function getQueryClassPath();

    /**
     * @param array $parameterList
     */
    public function __construct(array $parameterList)
    {
        $this->parameterBag = new ParameterBag($parameterList);
    }

    /**
     * @param      $indexId
     * @param null $openIndexQuery
     *
     * @return QueryInterface
     */
    public function getQuery($indexId, $openIndexQuery = null)
    {
        $this->getParameterBag()->setParameter('indexId', $indexId);
        $this->getParameterBag()->setParameter('openIndexQuery', $openIndexQuery);
        $classPath = $this->getQueryClassPath();

        return new $classPath($this->getParameterBag()->getAsArray());
    }

    /**
     * @param string $parameterName
     * @param mixed  $defaultValue
     *
     * @return mixed
     */
    public function getParameter($parameterName, $defaultValue = null)
    {
        return $this->getParameterBag()->getParameter($parameterName, $defaultValue);
    }

    /**
     * @return ParameterBag
     */
    public function getParameterBag()
    {
        return $this->parameterBag;
    }

    /**
     * @return string|null
     */
    public function getDatabase()
    {
        return $this->getParameter('dbName');
    }

    /**
     * @return string|null
     */
    public function getTable()
    {
        return $this->getParameter('tableName');
    }

    /**
     * @return string|null
     */
    public function getIndex()
    {
        return $this->getParameter('indexName', 'PRIMARY');
    }

    /**
     * @return array
     */
    public function getColumnList()
    {
        return $this->getParameter('columnList', array());
    }

    /**
     * @return array
     */
    public function getFilterList()
    {
        return $this->getParameter('filterList', array());
    }

    /**
     * @return boolean
     */
    public function isValid()
    {
        //TODO
    }

    /**
     * @return array
     */
    public function getFilterColumnList()
    {
        return array();
    }
} 