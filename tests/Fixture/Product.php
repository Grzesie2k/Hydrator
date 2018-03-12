<?php

namespace Grzesie2k\Hydrator\Fixture;

// Test class alias
use Grzesie2k\Hydrator\Fixture\Store as Source;

class Product
{
    /** @var Source */
    private $store;

    /** @var int */
    private $quantity;

    /** @var string */
    private $name;

    /** @var string */
    private $description;

    /** @var float */
    private $price;

    public function __construct(
        Source $store,
        int $quantity,
        string $name,
        ?string $description,
        float $price
    )
    {
        $this->store = $store;
        $this->quantity = $quantity;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
    }
}
