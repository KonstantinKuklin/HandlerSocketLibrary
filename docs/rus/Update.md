Update через открытый индекс
------------
Команда Update является модифицирующей БД командой и может выполнятся только через пишущий сокет.

Открываем индекс с колонками `'key', 'text'`. Заменяем значения на `2, 'new'`.
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

Вы можете узнать сколько записей было модифицировано командой Update.
```php
if($updateQuery->getResult()->getNumberModifiedRows() == 0){
 // ни 1 запись не была модифицирована
}
```

Update с открытием индекса
------------
Данная команда проверит есть ли нужный индекс, если его нет, то сначала откроет, а затем выполнит Update.
Команда найдет все строчки, где key = 2 и заменит `key` на `2`, `text` на `new2`.

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

Update c помощью строителя запросов
------------
При инициализации указываем какие колонки на какие данные будут заменены.

через where указываем условия отсеивания.
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