Select from opened index
------------

Open a connection to the read socket, and authorize with the password `passwordRead`
```php
$reader = new \HS\Reader('localhost', 9998, 'passwordRead');
```

Select query can also be sent to writing socket.

Open the index to query with the database `database`, table `tableName`, use `PRIMARY` index and return 2 columns `key`, `text`.

```php
$indexId = $reader->getIndexId(
    'database',
    'tableName',
    'PRIMARY',
    array('key', 'text')
);
```

After we get the index (if the index was already open, we will get it ID, without reopening) execute Select query with `key = 42`

```php
$selectQuery = $reader->selectByIndex($indexId, Comparison::EQUAL, array(42));
```

Now the variable `$selectQuery` contain class `\HS\Query\SelectQuery` and we can further specify how we want to get the answer:

1) as an enumerated array (is the default value)

`$selectQuery->setReturnType(SelectQuery::VECTOR);`

2) as an associative array, where each record is located on a key column value

`$selectQuery->setReturnType(SelectQuery::ASSOC);`

To send all queries and get the results you need to do:

```php
$resultList = $reader->getResultList();
```

Another way to execute the query:
```php
$selectQuery->execute(); // query was sent and the results on this query and all from the queue were parsed
$selectResult = $selectQuery->getResult();
```

The variable `$resultList` contains a list of all results.
Just the desired result can be obtained:

```php
$selectResult = $selectQuery->getResult();
```

If the command has been successfully executed, the getData() method returns an array of arrays:

```php
$arrayResultList = $selectResult->getData();
```

If the command is unsuccessful, then the `getError ()` returns the class with an error, `null` if no error occurred:

```php
$selectResult->getError();
```

Select with the opening index
------------
This command will check whether there's a required index if it isn't, it'll first open, and then execute the select.

The query returns all the values ​​of `key, text`, where the `key` > 1

```php
$selectQuery = $reader->select(
    array('key', 'text'),
    $this->getDatabase(),
    $this->getTableName(),
    'PRIMARY',
    \HS\Component\Comparison::MORE,
    array('1')
);

$this->getReader()->getResultList();
$selectResult = $selectQuery->getResult();
if(!$selectResult->isSuccessfully()){
    // query returned with errors, see getError()
}

```

Select IN
------------
Analogue of the request:

`USE $this->getDatabase();`

`SELECT 'key', 'date', 'float', 'varchar', 'text', 'set', 'null', 'union' FROM $this->getTableName() WHERE key in (42,100);`

```php
$selectQuery = $reader->selectIn(
    array('key', 'date', 'float', 'varchar', 'text', 'set', 'null', 'union'),
    $this->getDatabase(),
    $this->getTableName(),
    'PRIMARY',
    array(42, 100),
);

$this->getReader()->getResultList();

$selectResult = $selectQuery->getResult();
```

Select with Filter
------------
Analogue of the request:

`USE $this->getDatabase();`

`SELECT 'key', 'text' FROM $this->getTableName() WHERE key > 1 AND num = 3 LIMIT 0, 99;`

Please note that we have added array ('num'), that is, at the opening of the index will be added filtration column num with number 0.

When you create a class Filter specify the comparison operation (we have it =) and column filtering 0 (corresponding num), on the value 3.

Just in our request specified limit, if you are satisfied queries that may return a different number of data - always tells you the limit, otherwise you won't receive all the data.

```php
$selectQuery = $reader->select(
    array('key', 'text'),
    $this->getDatabase(),
    $this->getTableName(),
    'PRIMARY',
    Comparison::MORE,
    array('1'),
    0,
    99,
    array('num'),
    array(new Filter(Comparison::EQUAL, 0, '3'))
);

$this->getReader()->getResultList();
```

Select with QueryBuilder
------------

```php
$selectQueryBuilder = \HS\QueryBuilder::select(
    array('key', 'date', 'varchar', 'text', 'set', 'union')
)
    ->fromDataBase($this->getDatabase())
    ->fromTable($this->getTableName())
    ->where(Comparison::MORE, array('key' => 2))
    ->andWhere('float', Comparison::EQUAL, 3);

$selectQuery = $reader->addQueryBuilder($selectQueryBuilder);
$reader->getResultList();

$selectQuery->getResult()->getData();
```