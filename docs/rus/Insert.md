Insert в открытый индекс
------------
Команда Insert является модифицирующей БД командой и может выполнятся только через пишущий сокет.

Открываем индекс с колонками `'key', 'date', 'float', 'varchar', 'text', 'set', 'union'`.

Вставляем данные по индексу, обязательно передаем массив значений.
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
Если вы полностью уверены в работоспособности вашей команды, мы можем ее просто отослать серверу и не читать ответ, тем самым сэкономить время и память:
```php
$writer->sendQueries();
```
Если вы хотите проверить, что команда выполнена удачно:
```php
$writer->getResultList();
if($insertQuery->getResult()->isSuccessfully()){
    // запрос удачно обработан
}
```

Insert с открытием индекса
------------
Вставка данных происходит в `'key', 'date', 'float', 'varchar', 'text', 'set', 'union'`.

Если соответствующего индекса нет, он будет открыт автоматически.

Будут вставлены значения `'468', '0000-00-01', '1.02', 'char', 'text468', '1', '1'`.
```php
$insertQuery = $writer->insert(
    array('key', 'date', 'float', 'varchar', 'text', 'set', 'union'),
    $this->getDatabase(),
    $this->getTableName(),
    'PRIMARY',
    array('468', '0000-00-01', '1.02', 'char', 'text468', '1', '1')
);
```
Другой способ выполнить запрос:
```php
$insertQuery->execute(); // отправлен запрос + получен ответ на этот запрос + все, что было в очереди на отправку
$insertResult = $insertQuery->getResult();
```

Insert через QueryBuilder
------------
При добавлении записей все массивы записей должны содержать одинаковые ключи.

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