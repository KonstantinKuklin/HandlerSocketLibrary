<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Query;

use HS\Component\Comparison;
use HS\Component\ComparisonInterface;
use HS\Component\Filter;
use HS\Component\InList;
use HS\Driver;
use HS\Exception\InvalidArgumentException;

class SelectQuery extends QueryAbstract
{
    const VECTOR = 1;
    const ASSOC = 2;

//  const OBJECT = 3; TODO

    public function getReturnType()
    {
        return $this->getParameter('returnType', self::VECTOR);
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
            $this->getParameterBag()->setParameter('returnType', $type);

        } else {
            throw new InvalidArgumentException("Got unknown return type.");
        }
    }

    /**
     * @return ComparisonInterface
     */
    public function getComparison()
    {
        return $this->getParameter('comparison', Comparison::EQUAL);
    }

    /**
     * @return array
     */
    public function getKeyList()
    {
        return $this->getParameter('keyList', array());
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->getParameter('limit', 1);
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->getParameter('offset', 0);
    }

    /**
     * @return string
     */
    public function getIn()
    {
        /** @var InList $inKeyList */
        $inKeyList = $this->getParameter('inKeyList');
        if ($inKeyList === null) {
            return '';
        }

        return sprintf(
            Driver::DELIMITER . "@" . // in marker
            Driver::DELIMITER . "%d" . // position
            Driver::DELIMITER . "%d" . // count
            Driver::DELIMITER . "%s", // key list
            $inKeyList->getPosition(),
            $inKeyList->getCount(),
            Driver::prepareSendDataStatic($inKeyList->getKeyList())
        );
    }

    /**
     * @return string
     */
    public function getFilterList()
    {
        /** @var Filter[] $filterList */
        $filterList = $this->getParameter('filterList');
        if ($filterList === null) {
            return '';
        }

        $output = '';
        foreach ($filterList as $filter) {
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