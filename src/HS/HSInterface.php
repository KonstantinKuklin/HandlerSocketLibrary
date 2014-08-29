<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;


interface HSInterface
{
    // Comparison Operators
    const EQUAL = '=';
    const MORE = '>';
    const MORE_AND = '>=';
    const LESS = '<';
    const LESS_AND = '<=';

    const FILTER_TYPE_SKIP = 'F';
    const FILTER_TYPE_STOP = 'W';
}