<?php

use PHPUnit\Framework\TestCase;
use Ceparadise168\Cart\Cart;

class CartTest extends TestCase
{
    public function testHello()
    {
        $string = 'hello';

        $this->assertEquals('hello', $string);
    }

    public function testPutItemToCartAndAssertCartItem()
    {
        $cart = new Cart();

        $item = $this->createItem('x', 100, 1);
        $cart->putItem($item);

        $this->assertEquals([], array_diff_assoc($item, $cart->getItems()[0]));
    }

    public function testGetCartTotal()
    {
        $cart = new Cart();

        $item = $this->createItem('x', 100, 1);
        $cart->putItem($item);

        $item = $this->createItem('y', 200, 2);
        $cart->putItem($item);

        $this->assertEquals(500, $cart->total());
    }

    public function testTotalQuantityShouldGetTotalQuantityOfCartItems()
    {
        $cart = new Cart();

        $item = $this->createItem('x', 100, 1);
        $cart->putItem($item);

        $item = $this->createItem('y', 200, 2);
        $cart->putItem($item);

        $this->assertEquals(3, $cart->getTotalQuantity());
    }

    public function createItem($name, $price, $qty)
    {
        return compact('name', 'price', 'qty');
    }
}
