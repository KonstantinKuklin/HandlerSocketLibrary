Decrement через открытый индекс
------------
Команда Decrement является модифицирующей БД командой и может выполнятся только через пишущий сокет.

Открываем индекс с колонками `'key', 'num'`. И уменьшаем значение колонки `key` на 0, колонки `num` на 3, где `key` = 106.

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
Если вы полностью уверены в работоспособности вашей команды, вы можете ее просто отослать серверу и не читать ответ, тем самым сэкономить время и память.
```php
$writer->sendQueries();
```
Если вы хотите проверить, что команда выполнена удачно.
```php
$writer->getResultList();
if($decrementQuery->getResult()->isSuccessfully()){
    // запрос удачно обработан
}
```

Другой способ выполнить запрос.
```php
$decrementQuery->execute(); // отправлен запрос + получен ответ на этот запрос + все, что было в очереди на отправку
$decrementResult = $decrementQuery->getResult();
```


Decrement с открытием индекса
------------
Данная команда проверит есть ли нужный индекс, если его нет - откроет, а затем выполнит `Decrement`.

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

Decrement c помощью строителя запросов
------------
При инициализации указываем какие колонки на сколько будут увеличены.

Если указано просто значение, то оно будет увеличено на 1.

Через 'Where' указываем условия отсеивания.
```php
$decrementQueryBuilder = QueryBuilder::decrement(array('key' => 0, 'num'))
->fromDataBase($this->getDatabase())
->fromTable($this->getTableName())
->where(Comparison::EQUAL, array('key' => 104));

$decrementQuery = $writer->addQueryBuilder($decrementQueryBuilder);
$writer->getResultList();
```