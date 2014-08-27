<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;


class DatabaseManager extends Manager
{
    private $database = null;

    /**
     * @param ReaderInterface   $reader
     * @param WriterHSInterface $writer
     * @param string            $database
     */
    public function __construct(ReaderInterface $reader = null, WriterHSInterface $writer = null, $database)
    {
        $this->database = $database;
        parent::__construct($reader, $writer);
    }


} 