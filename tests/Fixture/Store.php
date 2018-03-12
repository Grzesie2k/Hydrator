<?php

namespace Grzesie2k\Hydrator\Fixture;

class Store
{
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }
}
