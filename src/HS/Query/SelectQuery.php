<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
namespace HS\Query;

use HS\Component\Comparison;
use HS\Component\ComparisonInterface;
use HS\Component\Filter;
use HS\Component\InList;
use HS\Exception\WrongParameterException;

class SelectQuery extends QueryAbstract
{
    const VECTOR = 1;
    const ASSOC = 2;

//    const OBJECT = 3;

    public function getReturnType()
    {
        return $this->getParameter('returnType', self::VECTOR);
    }

    /**
     * @param int $type
     *
     * @throws WrongParameterException
     */
    public function setReturnType($type)
    {
        if ($type === self::ASSOC
            || $type === self::VECTOR
//            || $type === self::OBJECT
        ) {
            $this->getParameterBag()->setParameter('returnType', $type);

        } else {
            throw new WrongParameterException("Got unknown return type.");
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
     * @return array
     */
    public function getIn()
    {

        /** @var InList $inKeyList */
        $inKeyList = $this->getParameter('inKeyList');
        if ($inKeyList === null) {
            return array();
        }

        $output[] = '@';

        return array_merge(
            array(
                '@',
                $inKeyList->getPosition(),
                $inKeyList->getCount()
            ),
            $inKeyList->getKeyList()
        );

    }

    public function getFilterList()
    {
        /** @var Filter[] $filterList */
        $filterList = $this->getParameter('filterList');
        if ($filterList === null) {
            return array();
        }

        $output = array();
        foreach ($filterList as $filter) {
            $output[] = $filter->getType();
            $output[] = $filter->getComparison();
            $output[] = $filter->getPosition();
            $output[] = $filter->getKey();
        }

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryParameters()
    {
        // <indexid> <op> <vlen> <v1> ... <vn> [LIM] [IN] [FILTER ...]

        return array_merge(
            array(
                $this->getIndexId(),
                $this->getComparison(),
                count($this->getKeyList()),
            ),
            $this->getKeyList(),
            array(
                $this->getLimit(),
                $this->getOffset()
            ),
            $this->getIn(),
            $this->getFilterList()
        );
    }
}