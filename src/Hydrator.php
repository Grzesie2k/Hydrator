<?php

namespace Grzesie2k\Hydrator;

interface Hydrator
{
    /**
     * @param mixed $data
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function hydrate($data);
}
