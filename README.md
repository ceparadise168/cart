建立 Cart 套件
===

## 目標:
- [x] 設計一個購物車物件，這個物件可以增加商品、計算總價、回傳商品總數量

- [x] 設計成能以 PSR-4 載入的套件

- [ ] 重構商品

- [ ] 重構購物車

- [ ] 增加購物車功能

- [ ] 發佈套件

## 建立專案

    composer create-project laravel/laravel Cart --prefer-dist

移動到專案目錄下

    cd Cart

## 建立套件
建立套件目錄，格式為 packges/開發商(作者)/套件名稱，並建立兩個資料夾分別為 src、tests

```
    mkdir -p packges/ceparadise168/cart/src
    
    mkdir -p packges/ceparadise168/cart/test

    cd packges/ceparadise168/cart
``` 

初始化 composer 設定，安裝 phpunit
```
    composer init

    composer require phpunit/phpunit --dev
```

composer.json 如下
```
{
    "name": "ceparadise168/cart",
    "authors": [
        {
            "name": "Eric Tu",
            "email": "ceparadise168@gmail.com"
        }
    ],
    "require": {},
    "require-dev": {
        "phpunit/phpunit": "^8.5"
    },
    "autoload": {
        "psr-4": {
            "Ceparadise168\\Cart\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Ceparadise168\\Cart\\Tests\\": "tests/"
        }
    }
}

```

讓 composer 根據我們指定的方式產生映射

```
composer dump-autoload
```

可以到這邊看結果
```
packges\ceparadise168\cart\vendor\composer\autoload_psr4.php
```

新增 packges\ceparadise168\cart\src\Cart.php
```
<?php

namespace Ceparadise168\Cart;

class Cart
{
    private $items = [];
    public function result()
    {
        return 'test';
    }
}
```

## 驗證套件

回到專案根目錄底下的 composer.json，在 autoload 的地方加入對應資訊， 

格式為 "Vendor\\Namespace\\": "path"

```
    "autoload": {
        "psr-4": {
            "App\\": "app/",
            "Ceparadise168\\Cart\\": "packges/ceparadise168/cart/src"
        },
        "classmap": [
            "database/seeds",
            "database/factories"
        ]
    },
```

    composer dump-autoload

在 routes\web.php 中修改 / 回傳方便驗證結果

```
<?php

use Illuminate\Support\Facades\Route;
use Ceparadise168\Cart\Cart;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    /*
    return view('welcome');
    */
    $cart = new Cart();
    return $cart->result();
});
```

利用 php artisan serve 啟動專案並訪問 http://127.0.0.1:8000，就可以看到 test 了

```
$ php artisan serve
Laravel development server started: http://127.0.0.1:8000
[Wed Jul  8 07:11:15 2020] 127.0.0.1:55141 [200]: /favicon.ico

```

---

## 規劃及開發 Cart 套件

### 準備測試環境

回到套件目錄準備測試環境

    cd packges/ceparadise168/cart

先前已經安裝過 phpunit 了，直接下指令生成 phpunit.xml

```
$ vendor/bin/phpunit --generate-configuration
PHPUnit 8.5.8 by Sebastian Bergmann and contributors.

Generating phpunit.xml in C:\Users\cepar\Desktop\projects\TQ\Cart\packges\ceparadise168\cart

Bootstrap script (relative to path shown above; default: vendor/autoload.php):
Tests directory (relative to path shown above; default: tests):
Source directory (relative to path shown above; default: src):

Generated phpunit.xml in C:\Users\cepar\Desktop\projects\TQ\Cart\packges\ceparadise168\cart
```

建立一個 CartTest

```
touch tests/CartTest.php
```


```
<?php

use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testHello()
    {
        $string = 'hello';

        $this->assertEquals('hello', $string);
    }
}
```

執行測試確認測試功能正常

```
$ ./vendor/bin/phpunit
PHPUnit 8.5.8 by Sebastian Bergmann and contributors.

Runtime:       PHP 7.2.31 with Xdebug 2.8.1
Configuration: C:\Users\cepar\Desktop\projects\TQ\Cart\packges\ceparadise168\cart\phpunit.xml

.                                                                   1 / 1 (100%)

Time: 489 ms, Memory: 4.00 MB

OK (1 test, 1 assertion)

```

---

採用 TDD 方式新增購物車基本功能
- 增加商品
- 取得所有商品
- 計算總價
- 計算總數量

撰寫測試增加商品到購物車的測試
```
    public function testPutItemToCartAndAssertCartItem()
    {
        $cart = new Cart();
        $item = [
            'name' => 'pen',
            'price' => 100,
            'qty' => 1
        ];
        $cart->putItem($item);

        $this->assertEquals([], array_diff_assoc($item, $cart->getItems()));
    }
```

執行測試，發現得到紅燈，測試失敗。
因為 Cart 還沒有實作 putItem()、getItems()
```
$ ./vendor/bin/phpunit
PHPUnit 8.5.8 by Sebastian Bergmann and contributors.

Runtime:       PHP 7.2.31 with Xdebug 2.8.1
Configuration: C:\Users\cepar\Desktop\projects\TQ\Cart\packges\ceparadise168\cart\phpunit.xml

E.                                                                  2 / 2 (100%)

Time: 470 ms, Memory: 4.00 MB

There was 1 error:

1) CartTest::testPutItemToCartAndAssertCartItem
Error: Call to undefined method Ceparadise168\Cart\Cart::putItem()

C:\Users\cepar\Desktop\projects\TQ\Cart\packges\ceparadise168\cart\tests\CartTest.php:23

ERRORS!
Tests: 2, Assertions: 1, Errors: 1.

```

實作 putItem()、getItems()
```
<?php

namespace Ceparadise168\Cart;

class Cart
{
    private $items = [];
    public function result()
    {
        return 'test';
    }

    public function putItem($item = [])
    {
        $this->items[] = $item;

        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }
}

```

再次測試，得到綠燈，測試通過。

```
$ ./vendor/bin/phpunit
PHPUnit 8.5.8 by Sebastian Bergmann and contributors.

Runtime:       PHP 7.2.31 with Xdebug 2.8.1
Configuration: C:\Users\cepar\Desktop\projects\TQ\Cart\packges\ceparadise168\cart\phpunit.xml

..                                                                  2 / 2 (100%)

Time: 461 ms, Memory: 4.00 MB

OK (2 tests, 2 assertions)

```


測試計算總價

```
    public function testGetCartTotal()
    {
        $cart = new Cart();

        $item = [
            'name' => 'pen',
            'price' => 100,
            'qty' => 1
        ];
        $cart->putItem($item);

        $item = [
            'name' => 'eraser',
            'price' => 200,
            'qty' => 2
        ];
        $cart->putItem($item);

        $this->assertEquals(500, $cart->total());
    }

```

紅燈

```
$ ./vendor/bin/phpunit
PHPUnit 8.5.8 by Sebastian Bergmann and contributors.

Runtime:       PHP 7.2.31 with Xdebug 2.8.1
Configuration: C:\Users\cepar\Desktop\projects\TQ\Cart\packges\ceparadise168\cart\phpunit.xml

..E                                                                 3 / 3 (100%)

Time: 474 ms, Memory: 4.00 MB

There was 1 error:

1) CartTest::testGetCartTotal
Error: Call to undefined method Ceparadise168\Cart\Cart::total()

C:\Users\cepar\Desktop\projects\TQ\Cart\packges\ceparadise168\cart\tests\CartTest.php:46

ERRORS!
Tests: 3, Assertions: 2, Errors: 1.

```

增加計算總價方法

```
    public function total()
    {
        $items = $this->getItems();

        $total = 0;

        foreach ($items as $item) {
            $total += $item['price'] * $item['qty'];
        }

        return $total;
    }
```

綠燈

```
$ ./vendor/bin/phpunit
PHPUnit 8.5.8 by Sebastian Bergmann and contributors.

Runtime:       PHP 7.2.31 with Xdebug 2.8.1
Configuration: C:\Users\cepar\Desktop\projects\TQ\Cart\packges\ceparadise168\cart\phpunit.xml

...                                                                 3 / 3 (100%)

Time: 469 ms, Memory: 4.00 MB

OK (3 tests, 3 assertions)

```


測試計算商品數量

```
    public function testTotalQuantityShouldGetTotalQuantityOfCartItems()
    {
        $cart = new Cart();

        $item = [
            'name' => 'pen',
            'price' => 100,
            'qty' => 1
        ];
        $cart->putItem($item);

        $item = [
            'name' => 'eraser',
            'price' => 200,
            'qty' => 2
        ];
        $cart->putItem($item);

        $this->assertEquals(500, $cart->getTotalQuantity());
    }
```

紅燈

```
$ ./vendor/bin/phpunit
PHPUnit 8.5.8 by Sebastian Bergmann and contributors.

Runtime:       PHP 7.2.31 with Xdebug 2.8.1
Configuration: C:\Users\cepar\Desktop\projects\TQ\Cart\packges\ceparadise168\cart\phpunit.xml

...E                                                                4 / 4 (100%)

Time: 471 ms, Memory: 4.00 MB

There was 1 error:

1) CartTest::testTotalQuantityShouldGetTotalQuantityOfCartItems
Error: Call to undefined method Ceparadise168\Cart\Cart::getTotalQuantity()

C:\Users\cepar\Desktop\projects\TQ\Cart\packges\ceparadise168\cart\tests\CartTest.php:67

ERRORS!
Tests: 4, Assertions: 3, Errors: 1.

```

增加計算商品數量方法

```
    public function getTotalQuantity()
    {
        $items = $this->getItems();

        $totalQuantity = 0;

        foreach ($items as $item) {
            $totalQuantity += $item['qty'];
        }

        return $totalQuantity;
    }
```

綠燈

```
$ ./vendor/bin/phpunit
PHPUnit 8.5.8 by Sebastian Bergmann and contributors.

Runtime:       PHP 7.2.31 with Xdebug 2.8.1
Configuration: C:\Users\cepar\Desktop\projects\TQ\Cart\packges\ceparadise168\cart\phpunit.xml

....                                                                4 / 4 (100%)

Time: 465 ms, Memory: 4.00 MB

OK (4 tests, 4 assertions)
```


References:

[Laravel Packages Development 套件開發](http://kejyun.github.io/Laravel-5-Learning-Notes-Books/package/development/package-development-README.html)

[【Laravel 5】撰寫你的package](https://medium.com/back-ends/laravel-5-%E6%92%B0%E5%AF%AB%E4%BD%A0%E7%9A%84package-458c93c279bc)

[開發 Laravel 獨立套件，透過 Composer 發佈到公司內部重複使用](https://devs.tw/post/186)

[單元測試開發購物車功能 系列](https://ithelp.ithome.com.tw/users/20065818/ironman/1673)

[composer.json 架构](https://docs.phpcomposer.com/04-schema.html)
[Composer 自动加载原理分析](http://silverd.cn/2018/06/02/composer-autoload.html)
