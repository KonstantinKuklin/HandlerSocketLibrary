<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;

use HS\Builder\DecrementQueryBuilder;
use HS\Builder\DeleteQueryBuilder;
use HS\Builder\IncrementQueryBuilder;
use HS\Builder\InsertQueryBuilder;
use HS\Builder\SelectQueryBuilder;
use HS\Builder\UpdateQueryBuilder;

class QueryBuilder
{
    /**
     * @param $columns
     *        "id,text,varchar"
     *
     * @return SelectQueryBuilder
     */
    public static function select($columns)
    {
        return new SelectQueryBuilder($columns);
    }

    /**
     * @param $columns
     *        "1,45,78"
     *
     * @return SelectQueryBuilder
     */
    public static function delete($columns)
    {
        return new DeleteQueryBuilder($columns);
    }

    /**
     * @param $columns
     *          ('id' => 1, 'text' => 'example')
     *
     * @return UpdateQueryBuilder
     */
    public static function update($columns)
    {
        return new UpdateQueryBuilder($columns);
    }

    /**
     * @param $columns
     *          ('id' => 1, 'text' => 'example')
     *
     * @return InsertQueryBuilder
     */
    public static function insert($columns)
    {
        return new InsertQueryBuilder($columns);
    }

    /**
     * @param $columns
     *          ('id' => 1, 'text' => 'example')
     *
     * @return IncrementQueryBuilder
     */
    public static function increment($columns)
    {
        return new IncrementQueryBuilder($columns);
    }

    /**
     * @param $columns
     *          ('id' => 1, 'text' => 'example')
     *
     * @return DecrementQueryBuilder
     */
    public static function decrement($columns)
    {
        return new DecrementQueryBuilder($columns);
    }
} 