Insert to opened index
------------
Insert command is modifying database and can only be done through writing socket.

Open an index with columns `'key', 'date', 'float', 'varchar', 'text', 'set', 'union'`.

Paste the data on the index, certainly pass an array of values.

```php
$writer = new \HS\Writer('localhost', 9999);
$indexId = $writer->getIndexId(
    $this->getDatabase(),
    $this->getTableName(),
    'PRIMARY',
    array('key', 'date', 'float', 'varchar', 'text', 'set', 'union')
);
$insertQuery = $writer->insertByIndex(
    $indexId,
    array('467', '0000-00-01', '1.02', 'char', 'text467', '1', '1')
);
```

If you are fully confident in your query, you can simply send it to server, thus save time and memory:

```php
$writer->sendQueries();
```

If you want to check that the command completed successfully:

```php
$writer->getResultList();
if($insertQuery->getResult()->isSuccessfully()){
    // query successfully processed
}
```

Insert with the opening index
------------
Inserting data occurs in the `'key', 'date', 'float', 'varchar', 'text', 'set', 'union'`.

If there is no corresponding index, it'll be opened automatically.

Will be inserted values ​​`'468', '0000-00-01', '1.02', 'char', 'text468', '1', '1'`.

```php
$insertQuery = $writer->insert(
    array('key', 'date', 'float', 'varchar', 'text', 'set', 'union'),
    $this->getDatabase(),
    $this->getTableName(),
    'PRIMARY',
    array('468', '0000-00-01', '1.02', 'char', 'text468', '1', '1')
);
```

Another way to execute the query:
```php
$insertQuery->execute(); // query was sent and the results on this query and all from the queue were parsed
$insertResult = $insertQuery->getResult();
```

Insert with QueryBuilder
------------
When adding entries to all arrays of records must contain the same keys.

```php
$insertQueryBuilder = \HS\QueryBuilder::insert();
$insertQueryBuilder
->toDatabase($this->getDatabase())
->toTable($this->getTableName())
->addRow(array(
      'key' => '123',
      'date' => '0000-00-00',
      'float' => '1.02',
      'varchar' => 'char',
      'text' => 'text',
      'set' => 'a',
      'union' => 'a',
    )
);

$insertQuery = $writer()->addQueryBuilder($insertQueryBuilder);
```