<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

use HS\QueryAbstract;
use HS\WriterHSInterface;

abstract class ModifyQueryAbstract extends QueryAbstract
{
    protected $indexId = null;
    protected $comparisonOperation = null;
    protected $keys = null;
    protected $limit = null;
    protected $offset = null;
    protected $values = null;
    protected $openIndexQuery = null;
    protected $calledClass = null;

    /**
     * @param int                 $indexId
     * @param string              $comparisonOperation
     * @param array               $keys
     * @param int|null            $offset
     * @param int|null            $limit
     * @param null|OpenIndexQuery $openIndexQuery
     * @param array               $values
     */
    public function __construct(
        $indexId, $comparisonOperation, $keys, $offset = null, $limit = null, $openIndexQuery = null,
        array $values = array()
    ) {
        $this->indexId = $indexId;
        $this->comparisonOperation = $comparisonOperation;
        $this->keys = $keys;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->openIndexQuery = $openIndexQuery;
        $this->values = $values;
        $this->calledClass = get_called_class();
    }

    /**
     * @return null|string
     */
    public function getModificator()
    {
        $mod = null;
        if ($this->calledClass == 'HS\Query\DeleteQuery') {
            $mod = WriterHSInterface::COMMAND_DELETE;
        } elseif ($this->calledClass == 'HS\Query\UpdateQuery') {
            $mod = WriterHSInterface::COMMAND_UPDATE;
        } elseif ($this->calledClass == 'HS\Query\DecrementQuery') {
            $mod = WriterHSInterface::COMMAND_DECREMENT;
        } elseif ($this->calledClass == 'HS\Query\IncrementQuery') {
            $mod = WriterHSInterface::COMMAND_INCREMENT;
        }

        return $mod;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return ($this->limit === null) ? 1 : $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return ($this->offset === null) ? 0 : $this->offset;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryParameters()
    {
        // <indexid> <op> <vlen> <v1> ... <vn> [LIM] [IN] [FILTER ...] MOD

        return array_merge(
            array(
                $this->indexId,
                $this->comparisonOperation,
                count($this->keys)
            ),
            $this->keys,
            array(
                $this->getLimit(),
                $this->getOffset(),
                $this->getModificator()
            ),
            $this->values
        );
    }

    /**
     * @return int
     */
    public function getIndexId()
    {
        return $this->indexId;
    }

    /**
     * @param array $data
     */
    public function setResultData($data)
    {
        $resultClass = null;
        if ($this->calledClass == 'HS\Query\DeleteQuery') {
            $resultClass = 'Delete';
        } elseif ($this->calledClass == 'HS\Query\UpdateQuery') {
            $resultClass = 'Update';
        } elseif ($this->calledClass == 'HS\Query\DecrementQuery') {
            $resultClass = 'Decrement';
        } elseif ($this->calledClass == 'HS\Query\IncrementQuery') {
            $resultClass = 'Increment';
        }

        $resultClass = 'HS\Result\\' . $resultClass . 'Result';
        $this->result = new $resultClass($this, $data, $this->openIndexQuery);
    }
}