[![Build Status](https://travis-ci.org/KonstantinKuklin/HandlerSocketLibrary.svg?branch=master)](https://travis-ci.org/KonstantinKuklin/HandlerSocketLibrary)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/KonstantinKuklin/HandlerSocketLibrary/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/KonstantinKuklin/HandlerSocketLibrary/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/KonstantinKuklin/HandlerSocketLibrary/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/KonstantinKuklin/HandlerSocketLibrary/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/konstantin-kuklin/handlersocket-library/v/stable.png)](https://packagist.org/packages/konstantin-kuklin/handlersocket-library)
[![Total Downloads](https://poser.pugx.org/konstantin-kuklin/handlersocket-library/downloads.png)](https://packagist.org/packages/konstantin-kuklin/handlersocket-library)

[Rus docs](README.rus.md)

Introduction
------------
HandlerSocket plugin for MySQL has been presented in 2010. Plugin greatly speeds up the data stored in MySQL, and allowed to
use this database as a NoSQL storage.

This library is an implementation of the protocol HandlerSocket completely written in PHP.

Motivation
------------
In the yard in 2014, and is still the tool and manuals for your HandlerSocket similar to the experimental or under development.
I have not had a ready-made solution, so I decided to write my bike and ride it.

Why you should use HandlerSocket:
- Data consistency
- High performance
- Compact protocol
- Compatible with MySQL replication
- Comes out of the box in PerconaServer, MariaDB

|                       | approx qps | User CPU util     |      System CPU util |
| --------------------- |:----------:| -----------------:|---------------------:|
|MySQL via SQL          |105,000     |60%                |28%                   |
|Memcached              |420,000     |8%                 |88%                   |
|MySQL via HandlerSocket|750,000     |45%                |53%                   |

How to install
------------
How to install HandlerSocket can be found on the internet or just download PerconaServer or MariaDB

Start using HandlerSocketLibrary is very simple:
- You need a composer
- Composer require "konstantin-kuklin/handlersocket-library": "dev-master"

How to connect
------------
Open connection to the read only socket and to authorize the password 'passwordRead'

```php
$ reader = new \HS\Reader('localhost', 9998, 'passwordRead');
```

Open connection to the write socket, no password is specified

```php
$ writer = new \HS\Writer('localhost', 9999);
```

Queries
------------
- [Select](docs/eng/Select.md)

- [Insert](docs/eng/Insert.md)

- [Update](docs/eng/Update.md)

- [Delete](docs/eng/Delete.md)

- [Increment](docs/eng/Increment.md)

- [Decrement](docs/eng/Decrement.md)

Benchmarks
------------
Comparison with the basic libraries for PHP.

The list will participate HSPHP, written in PHP, HandlerSocket, written in C.

HSPHP (PHP)
HandlerSocket (c-extension)
HandlerSocketLibrary (PHP)

How to help
------------
I will be glad to your suggestions and reports of bugs found!

TODO
------------
 - The library covers the entire functionality HandlerSocket`a except suffix "?".
 - Filters are temporarily unavailable on teams update.
 - The work with NULL is not good.
 - Single query execution.
 - Cover all possibly errors with error classes.
