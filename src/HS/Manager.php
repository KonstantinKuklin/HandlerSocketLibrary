<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;


class Manager
{

    private $reader = null;
    private $writer = null;

    /**
     * @param ReaderInterface $reader
     * @param WriterHSInterface $writer
     */
    public function __construct(ReaderInterface $reader = null, WriterHSInterface $writer = null)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    /**
     * @param $database
     *
     * @return DatabaseManager
     */
    public function getDataBaseManager($database)
    {
        return new DatabaseManager($this->reader, $this->writer, $database);
    }

    public function select($indexId, $comparisonOperation, $keys, $offset = 0, $limit = 0)
    {
        return $this->getReader()->selectByIndex($indexId, $comparisonOperation, $keys, $offset, $limit);
    }


    /**
     * @return ReaderInterface|WriterHSInterface
     * @throws \Exception
     */
    protected function getReader()
    {
        if ($this->reader !== null) {
            return $this->reader;
        }

        if ($this->writer !== null) {
            return $this->writer;
        }

        throw new \Exception("Reader and Writer won't set");
    }

    /**
     * @return WriterHSInterface
     * @throws \Exception
     */
    protected function getWriter()
    {
        if ($this->writer !== null) {
            return $this->writer;
        }

        throw new \Exception("Writer was not set");
    }
} 