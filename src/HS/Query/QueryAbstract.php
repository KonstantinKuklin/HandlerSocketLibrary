<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

use HS\Component\ParameterBag;
use HS\Driver;
use HS\Result\SelectResult;
use HS\Validator;

abstract class QueryAbstract implements QueryInterface
{
    private $parameterBag = null;
    protected $queryResultMap = array(
        'HS\Query\AuthQuery' => 'HS\Result\AuthResult',
        'HS\Query\TextQuery' => 'HS\Result\TextResult',
        'HS\Query\OpenIndexQuery' => 'HS\Result\OpenIndexResult',
        'HS\Query\SelectQuery' => 'HS\Result\SelectResult',
        'HS\Query\UpdateQuery' => 'HS\Result\UpdateResult',
        'HS\Query\InsertQuery' => 'HS\Result\InsertResult',
        'HS\Query\DeleteQuery' => 'HS\Result\DeleteResult',
        'HS\Query\IncrementQuery' => 'HS\Result\IncrementResult',
        'HS\Query\DecrementQuery' => 'HS\Result\DecrementResult',
    );

    /**
     * @param array $parameterList
     */
    public function __construct(array $parameterList)
    {
        $parameterList['queryClassName'] = get_called_class();
        $this->parameterBag = new ParameterBag($parameterList);


        // if indexId was set - check it
        if ($this->parameterBag->isExists('indexId')) {
            Validator::validateIndexId($this->getIndexId());
        }

        if ($this->parameterBag->isExists('dbName')) {
            Validator::validateDbName($this->getParameter('dbName'));
        }

        if ($this->parameterBag->isExists('tableName')) {
            Validator::validateTableName($this->getParameter('tableName'));
        }

        // init default values if it needs
        $this->initIndexName();

        if ($this->parameterBag->isExists('indexName')) {
            Validator::validateIndexName($this->getParameter('indexName'));
        }

    }

    private function initIndexName()
    {
        $indexName = $this->getParameter('indexName');
        if (!$this->getParameterBag()->isExists('indexName') || empty($indexName)) {
            $this->getParameterBag()->setParameter('indexName', 'PRIMARY');
        }
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
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->getParameter('resultObject', null);
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        return Driver::prepareSendDataStatic($this->getQueryParameters());
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getQueryParameters();

    /**
     * @return int
     */
    public function getIndexId()
    {
        return $this->getParameter('indexId');
    }

    /**
     * {@inheritdoc}
     */
    public function setResultData($data)
    {
        $queryClassName = $this->getQueryClassName();
        if ($queryClassName == 'HS\Query\SelectQuery') {
            $this->setSelectResultObject($data);

            return true;
        }

        $this->setResultObject($this->queryResultMap[$queryClassName], $data);
    }

    /**
     * @param string $className
     * @param mixed  $data
     */
    private function setResultObject($className, $data)
    {
        $this->getParameterBag()->setParameter(
            'resultObject',
            new $className($this, $data, $this->getParameter('openIndexQuery'))
        );
    }

    /**
     * @param mixed $data
     */
    private function setSelectResultObject($data)
    {

        $this->getParameterBag()->setParameter(
            'resultObject',
            new SelectResult(
                $this,
                $data,
                $this->getParameter('columnList', array()),
                $this->getParameter('returnType', SelectQuery::ASSOC),
                $this->getParameter('openIndexQuery')
            )
        );
    }

    /**
     * @return string
     */
    protected function getQueryClassName()
    {
        return $this->getParameter('queryClassName');
    }

    /**
     * @return ParameterBag
     */
    protected function getParameterBag()
    {
        return $this->parameterBag;
    }

} 