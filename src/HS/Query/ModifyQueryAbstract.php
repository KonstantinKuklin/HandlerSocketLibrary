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

    abstract public function getModificator();

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
    public function setResultData($data)
    {
        $queryClassName = $this->getQueryClassName();
        if ($this->isSuffix()) {
            $this->setSelectResultObject($data);
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

        if ($this->isSuffix()) {
            $mod .= '?';
        }
        $queryString .= Driver::DELIMITER . $mod . Driver::DELIMITER;

        return $queryString;
    }
}