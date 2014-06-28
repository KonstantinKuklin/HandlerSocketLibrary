<?php

namespace HS\Requests;

use HS\RequestAbstract;
use HS\Responses\OpenIndexResponse;

/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */
class OpenIndexRequest extends RequestAbstract
{
    private $indexId = null;
    private $dbName = null;
    private $tableName = null;
    private $indexName = null;
    private $columns = null;

    /**
     * Opening index
     *
     * The 'open_index' request has the following syntax.
     *
     * P <indexId> <dbName> <tableName> <indexName> <columns> [<fcolumns>]
     *
     * Once an 'open_index' request is issued, the HandlerSocket plugin opens the
     * specified index and keep it open until the client connection is closed. Each
     * open index is identified by <indexId>. If <indexId> is already open, the old
     * open index is closed. You can open the same combination of <dbName>
     * <tableName> <indexName> multiple times, possibly with different <columns>.
     *
     * For efficiency, keep <indexId> small as far as possible.
     *
     * @param int    $indexId
     *               Is a number in decimal.
     * @param string $dbName
     * @param string $tableName
     * @param string $indexName
     *               To open the primary key, use PRIMARY as $indexName.
     * @param array  $columns
     *               Is a array of column names.
     *
     * @return OpenIndexRequest
     */
    public function __construct($indexId, $dbName, $tableName, $indexName, $columns)
    {
        $this->indexId = $this->validateIndexId($indexId);
        $this->dbName = $this->validateDbName($dbName);
        $this->tableName = $this->validateTableName($tableName);

        // if no index use PRIMARY
        $this->indexName = $this->validateIndexName(empty($indexName) ? 'PRIMARY' : $indexName);
        $this->columns = $this->validateColumns($columns);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestParameters()
    {
        return array(
            'P',
            $this->indexId,
            $this->dbName,
            $this->tableName,
            $this->indexName,
            implode(',', $this->columns)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setResponseData($data)
    {
        $this->response = new OpenIndexResponse($this, $data);
    }

    /**
     * @param array $columns
     *
     * @throws \HS\Exceptions\WrongParameterException
     * @return array
     */
    protected function validateColumns($columns)
    {
        if (!$this->validateArray($columns, true)) {
            $this->getWrongParameterException(
                "Wrong columns value, must be array of values but can't contain array and object inside.",
                $columns
            );
        }

        return $columns;
    }
}