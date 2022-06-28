# symfony-demo

## Prerequirements

php 8.1

```bash
$ php -v
PHP 8.1.6 (cli) (built: May 12 2022 23:44:53) (NTS)
Copyright (c) The PHP Group
Zend Engine v4.1.6, Copyright (c) Zend Technologies
    with Zend OPcache v8.1.6, Copyright (c), by Zend Technologies
```

## 安装 symfony-cli 

macOS 使用 brew 安装 symfony-cli: 

$ brew install symfony-cli/tap/symfony-cli

查看： 

$ symfony -v

symfony 命令被安装在：

/usr/local/Cellar/symfony-cli/5.4.11/bin/symfony

PHP version 8.1.6  
Symfony CLI version 5.4.11

## 新建一个项目

使用 symfony 命令，创建一个名为 my-rest-api 的项目:

```bash
$ symfony new my-rest-api

* Creating a new Symfony project with Composer
  (running composer create-project symfony/skeleton my-rest-api  --no-interaction)
* Setting up the project under Git version control
  (running git init my-rest-api)
[OK] Your project is now ready in  my-rest-api
```

可以看到 symfony 命令里还是使用 composer create-project 命令来创建的项目。

可以查看一下生成的内容：

```bash 
$ ls
LICENSE     README.md   my-rest-api

$ ls -al my-rest-api
total 208
drwxr-xr-x  14 wangtom  staff    448  6 28 09:16 .
drwxr-xr-x   7 wangtom  staff    224  6 28 09:16 ..
-rw-r--r--   1 wangtom  staff    952  6 28 09:16 .env
drwxr-xr-x  12 wangtom  staff    384  6 28 09:16 .git
-rw-r--r--   1 wangtom  staff    189  6 28 09:16 .gitignore
drwxr-xr-x   3 wangtom  staff     96  6 28 09:16 bin
-rw-r--r--   1 wangtom  staff   1727  6 28 09:16 composer.json
-rw-r--r--   1 wangtom  staff  86158  6 28 09:16 composer.lock
drwxr-xr-x   8 wangtom  staff    256  6 28 09:16 config
drwxr-xr-x   3 wangtom  staff     96  6 28 09:16 public
drwxr-xr-x   4 wangtom  staff    128  6 28 09:16 src
-rw-r--r--   1 wangtom  staff   1595  6 28 09:16 symfony.lock
drwxrwxrwx   4 wangtom  staff    128  6 28 09:16 var
drwxr-xr-x   8 wangtom  staff    256  6 28 09:16 vendor
```

## 启动项目

使用 symfony serve 可以启动项目服务。

```bash
$ symfony serve 

或 
$ symfony server:start 
```

会默认启动 8000 端口，打开浏览器访问 http://127.0.0.1:8000

## 新建控制器：

安装 maker-bundle， 可以用来生成一些文件：

composer require symfony/maker-bundle --dev


新建控制器:

php bin/console make:controller ProductController

php bin/console make:controller UserController


该命令会直接生成 Controller 文件，位置在 src/Controller 目录。

生成的文件实例如下：

```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }
}
```

使用 curl 请求：

$ curl http://localhost:8000/product
{"message":"Welcome to your new controller!","path":"src\/Controller\/ProductController.php"}%

$ curl http://localhost:8000/user
{"message":"Welcome to your new controller!","path":"src\/Controller\/UserController.php"}%


## 新建实体类 entity

使用交互式命令行，生成 entity 文件，需要先安装 orm 包。

```bash
$ php bin/console make:entity
[ERROR] Missing package: to use the make:entity command, run:
composer require orm
```

需要先安装 orm 包:

$ composer require orm

该命令会引用 doctrine 相关的一系列包。

$ php bin/console make:entity 

创建 entity 文件时，同时也会生成 repository 文件： 

使用 ORM\Table 指定表名：

```php
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`test_users`')]
class User
{
}
```

## 创建数据库和测试库

在 mysql 中新添加用户，新创建数据库，并赋予权限。

```sql
-- 创建本项目专用的 项目数据库 和 测试库
CREATE DATABASE db_demo CHARSET=utf8mb4;
CREATE DATABASE db_demo_test CHARSET=utf8mb4;

-- 新建一个本项目专用的用户
CREATE USER 'dbUser1'@'%' IDENTIFIED WITH mysql_native_password BY 'Pwd-interview1';

-- 赋予权限（生成环境要注意安全，可配置仅有部分权限和仅本地连接）
GRANT ALL PRIVILEGES ON db_demo.* TO 'dbUser1'@'%';
GRANT ALL PRIVILEGES ON db_demo_test.* TO 'dbUser1'@'%';
```

创建表结构：

users  
products   


## 配置 MySQL 连接信息

在项目根目录 .env 文件中：

```
DATABASE_URL="mysql://user:pwd@127.0.0.1:3306/db_name?serverVersion=8.0&charset=utf8mb4"
```


## 测试

安装测试需要的库 `symfony/test-pack`: 

$ composer require --dev symfony/test-pack


安装成功，可以使用 PHPUnit 命令：

$ php bin/phpunit

使用 filter 参数过滤要执行的测试文件或方法：
 
$ php bin/phpunit --filter FunctionTest
$ php bin/phpunit --filter testGetUserList


可以使用 make:test 创建测试文件：

$ php bin/console make:test

执行测试： 

```bash
$ php bin/phpunit --filter FunctionTest
PHPUnit 9.5.18 #StandWithUkraine
Testing
.                    1 / 1 (100%)
Time: 00:00.037, Memory: 8.00 MB
OK (1 test, 3 assertions)
```


## 接口文档

1. 使用 swagger / openapi 生成 或 使用 swagger editor 编写

2. 自己编写 markdown

GET /users/

request params:

| field             | type     | remark  | 
| -----------       | ----     | ------  | 
| page              |  int     | current page number | 
| limit             |  int     | per page size   |
| is_active         |  int     | is_active   1, 0, -1  |
| is_member         |  int     | is_member   1, 0, -1|
| user_type         |  string  | user_type   1,2,3|
| login_start_Time  |  string  | 2015-12-12T06:31:08+00:00  |
| login_end_Time    |  string  | 2021-12-12T06:31:08+00:00  |


response data: 

http://localhost:8000/users?page=1&limit=2&is_active=1

```json
{
    "data":[
        {
            "id":195,
            "username":"test_195_user",
            "is_active":1,
            "is_member":0,
            "user_type":2,
            "last_login_at":{
                "date":"2022-03-30 13:27:00.000000",
                "timezone_type":3,
                "timezone":"UTC"
            }
        },
        {
            "id":206,
            "username":"test_206_user",
            "is_active":1,
            "is_member":0,
            "user_type":1,
            "last_login_at":{
                "date":"2022-02-15 13:35:29.000000",
                "timezone_type":3,
                "timezone":"UTC"
            }
        }
    ],
    "page":1,
    "limit":2
}
```

请求示例： 

http://localhost:8000/users?is_member=1&limit=2&page=3

http://localhost:8000/users?is_member=1&is_active=1&limit=2&page=3

http://localhost:8000/users?is_member=1&is_active=1&user_type=1,2&limit=2&page=3

http://localhost:8000/users?page=5&limit=2&is_member=1&login_start_time=2010-02-18T07:36:56&login_end_time=2021-02-18T07:36:56+00:00


## 添加索引：

对于经常用到的查询字段，要加上索引。

```bash
ALTER TABLE `test_users` 
 ADD INDEX `index_is_member` (`is_member`),
 ADD INDEX `index_user_type` (`user_type`),
 ADD INDEX `index_last_login_at` (`last_login_at`);
```
