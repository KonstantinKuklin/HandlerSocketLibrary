Open index
------------

Open a connection to the read socket and authorize with the password `passwordRead`

```php
$reader = new \HS\Reader('localhost', 9998, 'passwordRead');
```

When you open the index you must use a numeric identifier.

If the identifier wasn't used, it'll simply be assigned new index, if you have already used this index number, the new index just overwrite the old one.

Therefore it's very important to store information about all open indexes in the current connection, not to open several times indexes.

Below is an example of a method that checks and not open if we already has index, which we need, and if there's - just returns us to the identifier; but if there isn't - it opens a new index, and returns us to its identifier.

```php
$indexId = $reader->getIndexId(
    'database',
    'tableName',
    'PRIMARY',
    array('key', 'text')
);
$reader->getResultList();
```
If there's a situation when we need to open the index with the number 12 (for example) and we don't want to check:

```php
$openIndexQuery = $reader->openIndex(
    '12'
    'database',
    'tableName',
    'PRIMARY',
    array('key', 'text'),
    array('num')
);
$reader->getResultList();
```

Here we open the index with identifier 12, columns `key`,` text`, and the column to filter `num`.

This method returns us to the class OpenIndexQuery.

Another way to execute the query:
```php
$insertQuery->execute(); // query was sent and the results on this query and all from the queue were parsed
$insertResult = $insertQuery->getResult();
```