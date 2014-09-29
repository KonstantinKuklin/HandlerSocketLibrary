Select из открытого индекса
------------

Открываем соединение на сокет только для чления и авторизовываемся с паролем `passwordRead`
```php
$reader = new \HS\Reader('localhost', 9998, 'passwordRead');
```
Запрос Select также может быть выполнен и через пишущий сокет.

Открываем индекс для выполнения запросов из базы данных `database`, таблица `tableName`, используем `PRIMARY` индекс и возвращаем 2 колонки `key`, `text`.

```php
$indexId = $reader->getIndexId(
    'database',
    'tableName',
    'PRIMARY',
    array('key', 'text')
);
```
После открытия индекса (если индекс был уже открыт, то мы получим его ID без повторного переоткрывания) выполняем Select запрос со значением `key = 42`
```php
$selectQuery = $reader->selectByIndex($indexId, Comparison::EQUAL, array(42));
```
Теперь переменная `$selectQuery` является классом `\HS\Query\SelectQuery` и мы можем дополнительно указать, как мы хотим получить ответ:

1) в виде Нумерованного массива(является значением по умолчанию) 

`$selectQuery->setReturnType(SelectQuery::VECTOR);`

2) в виде Ассоциативного массива, где каждая запись будет расположена по ключу значения колонки

`$selectQuery->setReturnType(SelectQuery::ASSOC);`

Чтобы отправить все запросы и получить результаты нужно выполнить:
```php
$resultList = $reader->getResultList();
```
Иной способ выполнить запрос:
```php
$selectQuery->execute(); // отправлен запрос + получен ответ на этот запрос + все, что было в очереди на отправку
$selectResult = $selectQuery->getResult();
```
Переменная `$resultList` содержит список всех результатов. Также желаемый результат можно получить:
```php
$selectResult = $selectQuery->getResult();
```
Если команда была успешно выполнена, то метод `getData()` вернет массив массивов:
```php
$arrayResultList = $selectResult->getData();
```
Если команда была выполнена с ошибками, то `getError()` вернет класс с ошибкой ,`null` - если ошибки не было:
```php
$selectResult->getError();
```

Select с открытием индекса
------------
Данная команда проверит есть ли нужный индекс, если его нет - сначала откроет, а затем выполнит select.

Запрос возвращает все значения key,text, где key > 1
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
    // запрос не был выполнен коррект, смотри getError() чтобы разобраться
}

```

Select IN
------------
Аналог запроса:

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

Select с фильтром
------------
Аналог запроса:

`USE $this->getDatabase();`

`SELECT 'key', 'text' FROM $this->getTableName() WHERE key > 1 AND num = 3 LIMIT 0, 99;`

Обратите внимание, что мы добавили `array ('num')`, то есть при открытии индекса будет добавлена колонка фильтрации num, под номером 0.

При создании класса `Filter` указываем операцию сравнения (у нас это `=`), номер колонки фильтрации 0(соответствует num), дальше значение 3.

Также в нашем запросе указаны ЛИМИТЫ. Если вы выполняете запросы, которые могут возвращать разное количество данных - всегда указывайте ЛИМИТ, иначе будете получать не все данные.

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

Select c помощью строителя запросов
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