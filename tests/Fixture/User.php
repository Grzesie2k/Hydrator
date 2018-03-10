<?php

namespace Grzesie2k\Hydrator\Fixture;

class User
{
    private $id;
    private $email;
    private $products;

    /**
     * @param int $id
     * @param $email
     * @param Product[] $products
     */
    public function __construct(int $id, $email, $products)
    {
        $this->id = $id;
        $this->email = $email;
        $this->products = $products;
    }
}
