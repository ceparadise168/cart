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

    public function total()
    {
        $items = $this->getItems();

        $total = 0;

        foreach ($items as $item) {
            $total += $item['price'] * $item['qty'];
        }

        return $total;
    }

    public function getTotalQuantity()
    {
        $items = $this->getItems();

        $totalQuantity = 0;

        foreach ($items as $item) {
            $totalQuantity += $item['qty'];
        }

        return $totalQuantity;
    }
}
