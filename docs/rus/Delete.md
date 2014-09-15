Delete через открытый индекс
------------
Команда Delete является модифицирующей БД командой и может выполнятся только через пишущий сокет.

Открываем индекс с колонкой `'key'`.
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
Если вы полностью уверены в работоспособности нашей команды, вы можете ее просто отослать серверу и не читать ответ, тем самым сэкономить
и время и память.
```php
$writer->sendQueries();
```
Если вы хотите проверить, что команда выполнена удачно
```php
$writer->getResultList();
if($deleteQuery->getResult()->isSuccessfully()){
    // запрос удачно обработан
}
```

Другой способ выполнить запрос:
```php
$deleteQuery->execute(); // отправлен запрос + получен ответ на этот запрос + все, что было в очереди на отправку
$deleteResult = $deleteQuery->getResult();
```

Delete с открытием индекса
------------
Данная команда проверит есть ли нужный индекс, если его нет, то сначала откроет, а затем выполнит Delete.

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

Delete c помощью строителя запросов
------------
При инициализации указываем какие колонки на какие данные будут заменены.

через where указываем условия отсеивания.
```php
$deleteQueryBuilder = QueryBuilder::delete();
$deleteQueryBuilder
->fromDataBase($this->getDatabase())
->fromTable($this->getTableName())
->where(Comparison::EQUAL, array('key' => 5));

$deleteQuery = $writer->addQueryBuilder($deleteQueryBuilder);
$writer->getResultList();
```