Update through the open index
------------
Update command is modifying the database and can only be done through writing socket.

Open an index with columns `'key', 'text'`. Replace the values ​​with `2, 'new'`.
```php
$writer = new \HS\Writer('localhost', 9999);

$indexId = $writer->getIndexId(
    $this->getDatabase(),
    $this->getTableName(),
    'PRIMARY',
    array('key', 'text')
);
$updateQuery = $writer->updateByIndex($indexId, Comparison::EQUAL, array(2), array(2, 'new'));
```

You can find out how many records were modified command Update.

```php
if($updateQuery->getResult()->getNumberModifiedRows() == 0){
    // no one record has been modified
}
```

Update with the opening index
------------
This command will check whether there is a required index if it is not, first open and then perform Update.
It will find all lines where key = 2 and replace `key` on `2`, `text` on `new2`.

```php
$updateQuery = $writer->update(
    array('key', 'text'),
    $this->getDatabase(),
    $this->getTableName(),
    'PRIMARY',
    \HS\Component\Comparison::EQUAL,
    array(2),
    array(2, 'new2')
);
```

Another way to execute the query:
```php
$updateQuery->execute(); // query was sent and the results on this query and all from the queue were parsed
$updateResult = $updateQuery->getResult();
```

Update with QueryBuilder
------------
When initializing specify which columns to which data will be replaced.

where conditions indicate through screening.

```php
$updateQueryBuilder = \HS\QueryBuilder::update(
    array(
        'key' => 2,
        'varchar' => 'test update query'
    )
)
    ->fromDataBase($this->getDatabase())
    ->fromTable($this->getTableName())
    ->where(\HS\Component\Comparison::EQUAL, array('key' => 2));

$updateQuery = $this->getWriter()->addQueryBuilder($updateQueryBuilder);
```