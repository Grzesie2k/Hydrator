<?php

namespace Grzesie2k\Hydrator\HydratorStrategy;

use Grzesie2k\Hydrator\Hydrator;
use Grzesie2k\Hydrator\Hydrator\NullableHydrator;
use Grzesie2k\Hydrator\HydratorFactory;
use Grzesie2k\Hydrator\HydratorStrategy;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Nullable;

class NullableHydratorStrategy implements HydratorStrategy
{
    public function createHydrator(Type $type, HydratorFactory $hydratorFactory): ?Hydrator
    {
        if (!$type instanceof Nullable) {
            return null;
        }
        $actualType = $type->getActualType();
        $hydrator = $hydratorFactory->createHydrator($actualType);
        return new NullableHydrator($hydrator);
    }
}
