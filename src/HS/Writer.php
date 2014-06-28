<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS;


use HS\Requests\DecrementRequest;
use HS\Requests\DeleteRequest;
use HS\Requests\IncrementRequest;
use HS\Requests\InsertRequest;
use HS\Requests\UpdateRequest;

class Writer extends Reader implements WriterInterface
{
    const COMMAND_UPDATE = 'U';
    const COMMAND_DELETE = 'D';
    const COMMAND_INCREMENT = '+';
    const COMMAND_DECREMENT = '-';

    /**
     * {@inheritdoc}
     */
    public function update($indexId, $comparisonOperation, $keys, $values, $limit = 1, $offset = 0)
    {
        $updateRequest = new UpdateRequest(
            $indexId,
            $comparisonOperation,
            $keys,
            $values,
            $offset,
            $limit
        );

        $this->addRequestToQueue($updateRequest);

        return $updateRequest;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($indexId, $comparisonOperation, $keys, $limit = 1, $offset = 0)
    {
        $updateRequest = new DeleteRequest(
            $indexId,
            $comparisonOperation,
            $keys,
            $offset,
            $limit
        );

        $this->addRequestToQueue($updateRequest);

        return $updateRequest;
    }

    /**
     * {@inheritdoc}
     */
    public function increment($indexId, $comparisonOperation, $keys, $values, $limit = 1, $offset = 0)
    {
        $updateRequest = new IncrementRequest(
            $indexId,
            $comparisonOperation,
            $keys,
            $values,
            $offset,
            $limit
        );

        $this->addRequestToQueue($updateRequest);

        return $updateRequest;
    }

    /**
     * {@inheritdoc}
     */
    public function decrement($indexId, $comparisonOperation, $keys, $values, $limit = 1, $offset = 0)
    {
        $updateRequest = new DecrementRequest(
            $indexId,
            $comparisonOperation,
            $keys,
            $values,
            $offset,
            $limit
        );

        $this->addRequestToQueue($updateRequest);

        return $updateRequest;
    }

    /**
     * {@inheritdoc}
     */
    public function insert($indexId, $values)
    {
        $updateRequest = new InsertRequest(
            $indexId,
            $values
        );

        $this->addRequestToQueue($updateRequest);

        return $updateRequest;
    }
}