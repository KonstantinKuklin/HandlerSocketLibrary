<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;


class DatabaseManager extends Manager
{
    private $database = null;

    /**
     * @param ReaderInterface $reader
     * @param WriterInterface $writer
     * @param string          $database
     */
    public function __construct(ReaderInterface $reader = null, WriterInterface $writer = null, $database)
    {
        $this->database = $database;
        parent::__construct($reader, $writer);
    }


} 