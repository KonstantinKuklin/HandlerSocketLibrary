<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Component;

use HS\Exception\InvalidArgumentException;

class Filter
{
    const FILTER_TYPE_SKIP = 'F';
    const FILTER_TYPE_STOP = 'W';

    private $comparison;
    private $position = 0;
    private $key = null;
    private $type = self::FILTER_TYPE_SKIP;

    /**
     * @param ComparisonInterface|string $comparison
     * @param int                        $position
     * @param string                     $key
     * @param string                     $type
     *
     * @throws InvalidArgumentException
     */
    public function __construct($comparison, $position, $key, $type = self::FILTER_TYPE_SKIP)
    {
        if (!($comparison instanceof ComparisonInterface)) {
            $this->comparison = new Comparison($comparison);
        } else {
            $this->comparison = $comparison;
        }

        if (!is_numeric($position)) {
            throw new InvalidArgumentException("Position must be numeric");
        }
        $this->position = (int)$position;
        $this->key = $key;
        if ($type === self::FILTER_TYPE_SKIP || $type === self::FILTER_TYPE_STOP) {
            $this->type = $type;
        } else {
            throw new InvalidArgumentException("Filter type is wrong must be 'F' or 'W'.");
        }
    }

    /**
     * @return string
     */
    public function getComparison()
    {
        return $this->comparison->getComparison();
    }

    /**
     * @return ComparisonInterface
     */
    public function getComparisonObject()
    {
        return $this->comparison;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return array
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
} 