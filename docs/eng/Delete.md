Decrement through the open index
------------
Decrement command is modifying database and can only be done through writing socket.

Open an index with columns `'key'`.

```php
$writer = new \HS\Writer('localhost', 9999);

$indexId = $writer->getIndexId(
    $this->getDatabase(),
    $this->getTableName(),
    'PRIMARY',
    array('key')
);
$deleteQuery = $writer->deleteByIndex($indexId, \HS\Component\Comparison::EQUAL, array(3));
```

If you are fully confident in your query, you can simply send it to server, thus save time and memory.

```php
$writer->sendQueries();
```

If you want to check that the command completed successfully.

```php
$writer->getResultList();
if($deleteQuery->getResult()->isSuccessfully()){
    // query successfully processed
}
```

Delete with the opening index
------------
This command will check whether there's a required index if it isn't, first open and then perform Delete.

```php
$deleteQuery = $writer->delete(
    array('key'),
    $this->getDatabase(),
    $this->getTableName(),
    'PRIMARY',
    Comparison::EQUAL,
    array(1)
);

$writer->getResultList();
```

Another way to execute the query.
```php
$deleteQuery->execute(); // query was sent and the results on this query and all from the queue were parsed
$deleteResult = $deleteQuery->getResult();
```

Delete using QueryBuilder
------------
When initializing specify which columns and how much will be raised.

If you specify a value with out number,it'll be increased by 1.

'Where' conditions indicate through screening.

```php
$deleteQueryBuilder = QueryBuilder::delete();
$deleteQueryBuilder
->fromDataBase($this->getDatabase())
->fromTable($this->getTableName())
->where(Comparison::EQUAL, array('key' => 5));

$deleteQuery = $writer->addQueryBuilder($deleteQueryBuilder);
$writer->getResultList();
```