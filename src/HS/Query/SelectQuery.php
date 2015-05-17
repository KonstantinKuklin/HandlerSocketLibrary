<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Query;

use HS\Component\ComparisonInterface;
use HS\Component\InList;
use HS\Driver;
use HS\Exception\InvalidArgumentException;
use HS\Result\SelectResult;
use HS\Validator;

class SelectQuery extends QueryAbstract
{
    const VECTOR = 1;
    const ASSOC = 2;

//  const OBJECT = 3; TODO

    protected $comparisonOparation = null;
    protected $keyList = array();
    protected $offset = 0;
    protected $limit = 1;
    protected $columnList = array();
    protected $suffix = false;
    /** @var null|InList */
    protected $inKeyList = null;
    /** @var \HS\Component\Filter[] */
    protected $filterList = array();

    protected $returnType = self::ASSOC;

    /**
     * @param int                 $indexId
     * @param string              $comparisonOperation
     * @param array               $keyList
     * @param null                $socket
     * @param array               $columnList
     * @param null|int            $offset
     * @param null|int            $limit
     * @param null|OpenIndexQuery $openIndexQuery
     * @param null                $inKeyList
     * @param array               $filterList
     * @param null|int            $position
     */
    public function __construct(
        $indexId, $comparisonOperation, $keyList, $socket, array $columnList, $offset = null,
        $limit = null, $openIndexQuery = null, $inKeyList = null,
        array $filterList = array(), $position = null
    ) {
        parent::__construct();

        Validator::validateIndexId($indexId);
        $this->indexId = $indexId;

        $this->comparisonOparation = $comparisonOperation;
        $this->keyList = $keyList;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->columnList = $columnList;
        $this->openIndexQuery = $openIndexQuery;
        $this->socket = $socket;
        if ($inKeyList instanceof InList) {
            $this->inKeyList = $inKeyList;
        } elseif ($inKeyList !== null) {
            $this->inKeyList = new InList(is_numeric($position) ? $position : 0, $inKeyList);
        }
        $this->filterList = $filterList;
    }

    public function getReturnType()
    {
        return $this->returnType;
    }

    /**
     * @param int $type
     *
     * @throws InvalidArgumentException
     */
    public function setReturnType($type)
    {
        if ($type === self::ASSOC
            || $type === self::VECTOR
//          || $type === self::OBJECT TODO
        ) {
            $this->returnType = $type;
        } else {
            throw new InvalidArgumentException("Got unknown return type.");
        }
    }

    public function setResultData($data, $debug = false)
    {
        $this->setSelectResultObject($data, $debug);
    }

    /**
     * @param mixed $data
     * @param bool  $debug
     */
    protected function setSelectResultObject($data, $debug = false)
    {

        $this->resultObject = new SelectResult(
            $this,
            $data,
            $this->columnList,
            $this->returnType,
            $this->openIndexQuery,
            $debug
        );
    }

    /**
     * @return ComparisonInterface
     */
    public function getComparison()
    {
        return $this->comparisonOparation;
    }

    /**
     * @return array
     */
    public function getKeyList()
    {
        return $this->keyList;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return string
     */
    public function getIn()
    {
        if ($this->inKeyList === null) {
            return '';
        }

        return sprintf(
            Driver::DELIMITER . "@" . // in marker
            Driver::DELIMITER . "%d" . // position
            Driver::DELIMITER . "%d" . // count
            Driver::DELIMITER . "%s", // key list
            $this->inKeyList->getPosition(),
            $this->inKeyList->getCount(),
            Driver::prepareSendDataStatic($this->inKeyList->getKeyList())
        );
    }

    /**
     * @return string
     */
    public function getFilterList()
    {
        if (empty($this->filterList)) {
            return '';
        }

        $output = '';
        foreach ($this->filterList as $filter) {
            $output .= sprintf(
                Driver::DELIMITER . "%s" . // type
                Driver::DELIMITER . "%s" . // comparison
                Driver::DELIMITER . "%d" . // position
                Driver::DELIMITER . "%s", // key
                $filter->getType(),
                $filter->getComparison(),
                $filter->getPosition(),
                $filter->getKey()
            );
        }

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        // <indexid> <op> <vlen> <v1> ... <vn> [LIM] [IN] [FILTER ...]

        return sprintf(
            "%d" . Driver::DELIMITER . // index
            "%s" . Driver::DELIMITER . // comparison
            "%d" . Driver::DELIMITER . // key list count
            "%s" . Driver::DELIMITER . // key list
            "%d" . Driver::DELIMITER . // limit
            "%d" . // offset
            "%s" . // in list
            "%s", // filter list
            $this->getIndexId(),
            $this->getComparison(),
            count($this->getKeyList()),
            Driver::prepareSendDataStatic($this->getKeyList()),
            $this->getLimit(),
            $this->getOffset(),
            $this->getIn(),
            $this->getFilterList()
        );
    }
}