<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Query;

use HS\QueryAbstract;
use HS\Result\SelectResult;

class SelectQuery extends QueryAbstract
{
    const VECTOR = 1;
    const ASSOC = 2;
//    const OBJECT = 3;

    private $indexId = null;
    private $comparisonOperation = null;
    private $keys = null;
    private $limit = null;
    private $offset = null;
    private $indexColumns = null;
    private $in = null;

    // assoc array default value of return data
    private $returnType = self::ASSOC;

    /** @var null|OpenIndexQuery */
    private $openIndexQuery = null;

    /**
     * @param int                 $indexId
     * @param string              $comparisonOperation
     * @param array               $keys
     * @param int                 $limit
     * @param int                 $offset
     * @param array               $indexColumns
     * @param array               $in
     * @param null|OpenIndexQuery $openIndexQuery
     */
    public function __construct(
        $indexId, $comparisonOperation, $keys, $limit = 0, $offset = 0, $indexColumns, $in = array(),
        $openIndexQuery = null
    ) {
        $this->indexId = $indexId;
        $this->comparisonOperation = $comparisonOperation;
        $this->keys = $keys;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->indexColumns = $indexColumns;
        $this->in = $in;
        $this->openIndexQuery = $openIndexQuery;
    }

    /**
     * @param int $type
     *
     * @throws \Exception
     */
    public function setReturnType($type)
    {
        if ($type === self::ASSOC
            || $type === self::VECTOR
//            || $type === self::OBJECT
        ) {
            $this->returnType = $type;
        }

        throw new \Exception("Got unknown type ");
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryParameters()
    {
        // <indexid> <op> <vlen> <v1> ... <vn> [LIM] [IN] [FILTER ...]
        $invlen = count($this->in);
        $inMerge = array();
        if ($invlen) {
            $inMerge = array('@', '0');
            $inMerge = array_merge($inMerge, $this->in);
        }

        return array_merge(
            array(
                $this->indexId,
                $this->comparisonOperation,
                count($this->keys)
            ),
            $this->keys,
            array(
                $this->limit,
                $this->offset,
            ),
            $inMerge
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setResultData($data)
    {
        $this->Result = new SelectResult($this, $data, $this->indexColumns, $this->returnType, $this->openIndexQuery);
    }
}