<?php

namespace Grzesie2k\Hydrator\HydratorStrategy;

use Grzesie2k\Hydrator\Hydrator;
use Grzesie2k\Hydrator\Hydrator\PrimitiveHydrator;
use Grzesie2k\Hydrator\HydratorFactory;
use Grzesie2k\Hydrator\HydratorStrategy;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Float_;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\String_;

class PrimitiveHydratorStrategy implements HydratorStrategy
{
    public function createHydrator(Type $type, HydratorFactory $hydratorFactory = null): ?Hydrator
    {
        if ($type instanceof String_) {
            return new PrimitiveHydrator('string');
        }
        if ($type instanceof Integer) {
            return new PrimitiveHydrator('integer');
        }
        if ($type instanceof Float_) {
            return new PrimitiveHydrator('double');
        }

        return null;
    }
}
