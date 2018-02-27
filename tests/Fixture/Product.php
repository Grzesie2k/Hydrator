<?php

namespace Grzesie2k\Hydrator\Fixture;

class Product
{
    /** @var int */
    private $quantity;

    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var float */
    private $price;

    public function __construct(int $quantity, string $name, ?string $description, float $price)
    {
        $this->quantity = $quantity;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
    }
}
