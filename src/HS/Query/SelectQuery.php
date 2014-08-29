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
    private $in = array();

    // assoc array default value of return data
    private $returnType = self::ASSOC;

    /** @var null|OpenIndexQuery */
    private $openIndexQuery = null;

    /**
     * @param int                 $indexId
     * @param string              $comparisonOperation
     * @param array               $keys
     * @param int|null            $limit
     * @param int|null            $offset
     * @param array               $indexColumns
     * @param array               $in
     * @param null|OpenIndexQuery $openIndexQuery
     */
    public function __construct(
        $indexId, $comparisonOperation, $keys, $limit = null, $offset = null, $indexColumns, array $in = array(),
        $openIndexQuery = null
    ) {
        $this->indexId = $indexId;
        $this->comparisonOperation = $comparisonOperation;
        $this->keys = $keys;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->indexColumns = $indexColumns;

        // if old style of in
        if (!isset($in['icol']) && count($in) > 0) {
            $invlen = count($in);
            $this->in = array(
                'icol' => 1,
                'ivlen' => $invlen,
                'iv' => $in
            );
        } else {
            $this->in = $in;
        }

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

        } else {
            throw new \Exception("Got unknown type ");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryParameters()
    {
        // <indexid> <op> <vlen> <v1> ... <vn> [LIM] [IN] [FILTER ...]

        $lim = array();
        if ($this->limit !== null) {
            $lim[] = $this->limit;
        }
        if ($this->offset !== null) {
            $lim[] = $this->offset;
        }

        $inMerge = array();
        if (count($this->in) > 0) {
            $inMerge = array_merge(array('@', $this->in['icol'], $this->in['ivlen']), $this->in['iv']);
        }

        return array_merge(
            array(
                $this->indexId,
                $this->comparisonOperation,
                count($this->keys)
            ),
            $this->keys,
            $lim,
            $inMerge
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setResultData($data)
    {
        $this->result = new SelectResult($this, $data, $this->indexColumns, $this->returnType, $this->openIndexQuery);
    }
}