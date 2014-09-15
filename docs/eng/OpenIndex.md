Open index
------------

Open a connection to the read socket, and authorize with the password `passwordRead`

```php
$reader = new \HS\Reader('localhost', 9998, 'passwordRead');
```

When you open the index you must use a numeric identifier. If the identifier was not used, it will simply be assigned
new index, if you have already used this index number, the new index just overwrite the old one.

Therefore it is very important to store information about all open indexes in the current connection, not to open several times indexes.
An example of a method that checks and not open if we already has index, which we need, if so, just returns us to the identifier
if not, it opens a new index, and returns us to its identifier.

```php
$indexId = $reader->getIndexId(
    'database',
    'tableName',
    'PRIMARY',
    array('key', 'text')
);
```

If we just need to open the subscript c number 12 (for example), and we don't want to check:

```php
$indexIndexQuery = $reader->openIndex(
    '12'
    'database',
    'tableName',
    'PRIMARY',
    array('key', 'text'),
    array('num')
);
```

Here we open the index with identifier 12, columns `key`,` text`, and the column to filter `num`.
Method returns us to the class OpenIndexQuery.