<?php
namespace HS\Builder;

use HS\Component\ParameterBag;
use HS\Query\DeleteQuery;
use HS\Query\IncrementQuery;
use HS\Query\InsertQuery;
use HS\Query\QueryInterface;
use HS\Query\SelectQuery;
use HS\Query\UpdateQuery;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
abstract class QueryBuilderAbstract implements QueryBuilderInterface
{
    protected $dbName = '';
    protected $tableName = '';
    protected $indexName = 'PRIMARY';

    protected $indexId = null;
    protected $offset = 0;
    protected $limit = 1;

    protected $columnList = array();
    protected $valueList = array();
    protected $filterList = array();

    protected $comparison = null;
    protected $keyList = array();
    protected $inKeyList = null;
    protected $filterColumnList = array();

    protected $suffix = false;
    protected $returnType = SelectQuery::ASSOC;
    protected $openIndexQuery = null;

    /**
     * @return string
     */
    abstract public function getQueryClassPath();

    /**
     * {@inheritdoc}
     */
    public function getQuery($indexId, $socket, $openIndexQuery = null)
    {
        $this->indexId = $indexId;
        $this->openIndexQuery = $openIndexQuery;
        $classPath = $this->getQueryClassPath();

        $query = array();
        switch ($classPath) {
            case 'HS\Query\InsertQuery':
                foreach ($this->valueList as $rowList) {
                    $query[] = new InsertQuery($indexId, $rowList, $socket, $openIndexQuery);
                }
                break;
            case 'HS\Query\DeleteQuery':
                $query[] = new DeleteQuery(
                    $indexId,
                    $this->comparison,
                    $this->keyList,
                    $socket,
                    $this->columnList,
                    $this->offset,
                    $this->limit,
                    $openIndexQuery,
                    $this->inKeyList,
                    $this->filterList,
                    $this->suffix
                );
                break;
            case 'HS\Query\SelectQuery':
                $queryTmp = new SelectQuery(
                    $indexId,
                    $this->comparison,
                    $this->keyList,
                    $socket,
                    $this->columnList,
                    $this->offset,
                    $this->limit,
                    $openIndexQuery,
                    $this->inKeyList,
                    $this->filterList, null

                );
                $queryTmp->setReturnType($this->returnType);
                $query[] = $queryTmp;
                break;
            case 'HS\Query\UpdateQuery':
                $query[] = new UpdateQuery(
                    $indexId,
                    $this->comparison,
                    $this->keyList,
                    $socket,
                    $this->columnList,
                    $this->offset,
                    $this->limit,
                    $openIndexQuery,
                    $this->inKeyList,
                    $this->filterList,
                    $this->suffix,
                    $this->valueList
                );
                break;
            default:
                $query[] = new $classPath(
                    $indexId,
                    $this->comparison,
                    $this->keyList,
                    $socket,
                    $this->columnList,
                    $this->offset,
                    $this->limit,
                    $openIndexQuery,
                    $this->inKeyList,
                    $this->filterList,
                    $this->suffix,
                    $this->valueList
                );
                break;
        }

        return $query;
    }

    /**
     * @return string|null
     */
    public function getDatabase()
    {
        return $this->dbName;
    }

    /**
     * @return string|null
     */
    public function getTable()
    {
        return $this->tableName;
    }

    /**
     * @return string|null
     */
    public function getIndex()
    {
        return $this->indexName;
    }

    /**
     * @return array
     */
    public function getColumnList()
    {
        return $this->columnList;
    }

    /**
     * @return array
     */
    public function getFilterList()
    {
        return $this->filterList;
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