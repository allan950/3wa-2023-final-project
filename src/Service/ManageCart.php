<?php

namespace App\Service;

class ManageCart 
{

    private Array $cart;
    private float $total;
    private float $totalTTC;

    public function __construct()
    {
        $this->cart = [
            "items" => [],
            "count" => 0,
        ];
    }

    public function getCart() {
        //$this->updateCount();

        return $this->cart["items"];

    }

    public function getCount(): int {
        return count($this->cart['items']);
    }

    private function updateCount() {
        $this->cart["count"] = count($this->cart["items"]);
    }

    public function getTotalCount(): int {
        $count = 0;

        foreach($this->cart["items"] as $item) {
            $count = $count + $item["quantity"];
        }



        return $count;
    }

    private function calculateTotal() {
        $items = $this->cart["items"];

        $total = 0;

        foreach ($items as $item) {
            $total += $item["price"] * $item["quantity"];
        }

        $this->total = $total;
    }

    public function getTotal(): float {
        $this->calculateTotal();

        return $this->total;
    }

    public function getTotalTTC() {
        $totalHt = $this->getTotal();
        $totalTtc = $totalHt * 1;

        $this->totalTTC = $totalTtc;

        return $this->totalTTC;
    }

    public function getCartItem($id) {
        if ($this->cart["items"]) {
            foreach($this->cart["items"] as $cart) {
                if ($cart["id"] == $id) {
                    return $cart;
                }
            }
        } else {
            throw "There is no item in the cart";
        }
    }

    public function addItemToCart($product, $quantity) {
        $items = $this->cart["items"];
        $isItemNew = true;

        foreach ($items as $keys => $item) {
            if ($product["name"] === $item["name"] && $product["id"] === $item["id"]) {
                $isItemNew = false;
                $items[$keys]["quantity"] += $quantity;
            }
        }

        if ($isItemNew == true) {
            array_push($items, $product);
        }

        $this->cart["items"] = $items;
    }

    public function removeItem($item) {
        $items = $this->cart["items"];
        /* foreach ($this->cart["items"] as $key => $cart) {
            if ($cart === $item) {
                $items[$key] = "";
            }
        } */

        array_splice($items, array_search($item, $items), 1);

        $this->cart["items"] = $items;
    }
}