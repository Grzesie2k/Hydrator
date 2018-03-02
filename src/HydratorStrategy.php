<?php

namespace Grzesie2k\Hydrator;

use phpDocumentor\Reflection\Type;

interface HydratorStrategy
{
    public function createHydrator(Type $type, HydratorFactory $hydratorFactory): ?Hydrator;
}
