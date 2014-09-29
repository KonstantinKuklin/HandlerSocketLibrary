Decrement through the open index
------------
Decrement command is modifying database and can only be done through writing socket.

Open an index with columns `'key', 'num'`. And reduces the value of the column `key` by 0, the column `num` by 3, where `key` = 106.

```php
$writer = new \HS\Writer('localhost', 9999);

$indexId = $writer->getIndexId(
    $this->getDatabase(),
    $this->getTableName(),
    'PRIMARY',
    array('key', 'num')
);
$decrementQuery = $writer->decrementByIndex($indexId, '=', array(106), array(0, 3));
```

If you are fully confident in your query, you can simply send it to server, thus save time and memory:

```php
$writer->sendQueries();
```

If you want to check that the command completed successfully:

```php
$writer->getResultList();
if($decrementQuery->getResult()->isSuccessfully()){
    // query successfully processed
}
```

Another way to execute the query:
```php
$decrementQuery->execute(); // query was sent and the results on this query and all from the queue were parsed
$decrementResult = $decrementQuery->getResult();
```

Decrement with the opening index
------------
This command will check whether there's a required index if it isn't, first open and then perform Decrement.

```php
$incrementQuery = $writer->decrement(
    array('key', 'num'),
    $this->getDatabase(),
    $this->getTableName(),
    'PRIMARY',
    '=',
    array(106),
    array(0, 5)
);

$writer->getResultList();
```

Decrement using QueryBuilder
------------
When initializing specify which columns and how much will be raised.

If you specify a value with out number,it'll be decreased by 1.

'Where' conditions indicate through screening.

```php
$decrementQueryBuilder = QueryBuilder::decrement(array('key' => 0, 'num'))
->fromDataBase($this->getDatabase())
->fromTable($this->getTableName())
->where(Comparison::EQUAL, array('key' => 104));

$decrementQuery = $writer->addQueryBuilder($decrementQueryBuilder);
$writer->getResultList();
```
```