<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Component;

use HS\Exception\ComparisonException;

class Comparison implements ComparisonInterface
{
    // Comparison Operators
    const EQUAL = '=';
    const MORE = '>';
    const MORE_AND = '>=';
    const LESS = '<';
    const LESS_AND = '<=';

    private $comparison = self::EQUAL;

    /**
     * @param string $comparison
     *
     * @throws ComparisonException
     */
    public function __construct($comparison)
    {
        if ($comparison === self::EQUAL ||
            $comparison === self::MORE ||
            $comparison === self::MORE_AND ||
            $comparison === self::LESS ||
            $comparison === self::LESS_AND
        ) {
            $this->comparison = $comparison;
        } else {
            throw new ComparisonException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getComparison()
    {
        return $this->comparison;
    }
} 