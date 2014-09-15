[![Build Status](https://travis-ci.org/KonstantinKuklin/HandlerSocketLibrary.svg?branch=master)](https://travis-ci.org/KonstantinKuklin/HandlerSocketLibrary)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/KonstantinKuklin/HandlerSocketLibrary/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/KonstantinKuklin/HandlerSocketLibrary/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/KonstantinKuklin/HandlerSocketLibrary/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/KonstantinKuklin/HandlerSocketLibrary/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/konstantin-kuklin/handlersocket-library/v/stable.png)](https://packagist.org/packages/konstantin-kuklin/handlersocket-library)
[![Total Downloads](https://poser.pugx.org/konstantin-kuklin/handlersocket-library/downloads.png)](https://packagist.org/packages/konstantin-kuklin/handlersocket-library)

Введение
------------
В 2010 году был презентован плагин HandlerSocket для MySQL. Плагин значительно ускорял работу с данными, хранящимися в MySQL, и позволял работать с базой данных, как с NoSQL хранилищем.
Эта библиотека является реализацие протокола HandlerSocket полностью написанная на PHP.

Зачем
------------
На дворе 2014 год, а до сих пор инструмент и мануалы по использованию HandlerSocket похожи на экспериментальные или в процессе разработки. Меня не устроили уже готовые решения, поэтому я решил написать свой велосипед и кататься на нем.
Почему вам стоит использовать HandlerSocket:
- консистентность данных
- высокая производительность
- компактность протокола
- совместим с репликацией MySQL
- идет из коробки в PerconaServer, MariaDB

|                       | approx qps | User CPU util     |      System CPU util |
| --------------------- |:----------:| -----------------:|---------------------:|
|MySQL via SQL          |105,000     |60%                |28%                   |
|Memcached              |420,000     |8%                 |88%                   |
|MySQL via HandlerSocket|750,000     |45%                |53%                   |

Установка
------------
Как установить HandlerSocket можно найти в интернете или просто скачать PerconaServer или MariaDB

Начать использовать HandlerSocketLibrary очень просто:
- вам нужен composer
- composer require "konstantin-kuklin/handlersocket-library": "dev-master"

Подключение
------------
Открываем соединение на сокет только для чления и авторизовываемся с паролем 'passwordRead'

```php
$reader = new \HS\Reader('localhost', 9998, 'passwordRead');
```

Открываем соединение на сокет для записи, пароль не указан

```php
$writer = new \HS\Writer('localhost', 9999);
```

Запросы
------------
- [Open Index](docs/rus/OpenIndex.md)

- [Select](docs/rus/Select.md)

- [Insert](docs/rus/Insert.md)

- [Update](docs/rus/Update.md)

- [Delete](docs/rus/Delete.md)

- [Increment](docs/rus/Increment.md)

- [Decrement](docs/rus/Decrement.md)

Производительность
------------
Сравнение с основными библиотеками для PHP.

В списке будет участвовать HSPHP, написанный на PHP, HandlerSocket, написанный на С.

HSPHP (PHP)
HandlerSocket(c-extension)
HandlerSocketLibrary(PHP)

Как помочь
------------
Буду рад вашим предложениям и отчетам о найденных багах!

TODO
------------
 - Библиотека покрывает весь функционал HandlerSocket`а за исключением суффиксов "?".
 - Фильтры временно недоступны на командах обновления данных.
 - Некорректная обработка Null.
 - Выполнение единичных запросов.
 - Покрытие классами всех типов ошибок.
