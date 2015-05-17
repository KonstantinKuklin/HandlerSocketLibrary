<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Query;

use HS\Driver;

abstract class ModifyQueryAbstract extends SelectQuery
{
    protected $suffix = false;
    protected $valueList = array();

    /**
     * @return string
     */
    abstract public function getModificator();

    /**
     * @param int                                              $indexId
     * @param string                                           $comparisonOperation
     * @param array                                            $keyList
     * @param null|\HS\ReaderHSInterface|\HS\WriterHSInterface $socket
     * @param array                                            $columnList
     * @param null|int                                         $offset
     * @param null|int                                         $limit
     * @param null|\HS\Query\OpenIndexQuery                    $openIndexQuery
     * @param null|\HS\Component\InList                        $inKeyList
     * @param \HS\Component\Filter[]                           $filterList
     * @param bool                                             $suffix
     * @param array                                            $valueList
     */
    public function __construct(
        $indexId, $comparisonOperation, $keyList, $socket, array $columnList, $offset = null,
        $limit = null, $openIndexQuery = null, $inKeyList = null,
        array $filterList = array(), $suffix = false, $valueList = array()
    ) {
        parent::__construct(
            $indexId,
            $comparisonOperation,
            $keyList,
            $socket,
            $columnList,
            $offset,
            $limit,
            $openIndexQuery,
            $inKeyList,
            $filterList
        );

        $this->suffix = $suffix;
        $this->valueList = $valueList;
    }

    /**
     * {@inheritdoc}
     */
    public function setResultData($data, $debug = false)
    {
        $queryClassName = $this->getQueryClassName();
        if ($this->suffix) {
            $this->setSelectResultObject($data, $debug);
        } else {
            $this->setResultObject(self::$queryResultMap[$queryClassName], $data);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isSuffix()
    {
        return $this->suffix;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryString()
    {
        $queryString = parent::getQueryString();
        $mod = $this->getModificator();

        if ($this->suffix) {
            $mod .= '?';
        }
        $queryString .= Driver::DELIMITER . $mod . Driver::DELIMITER;

        return $queryString;
    }
}