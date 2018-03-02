<?php

namespace Grzesie2k\Hydrator\HydratorStrategy;

use Grzesie2k\Hydrator\Hydrator;
use Grzesie2k\Hydrator\Hydrator\ListHydrator;
use Grzesie2k\Hydrator\HydratorFactory;
use Grzesie2k\Hydrator\HydratorStrategy;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Array_;

class ListHydratorStrategy implements HydratorStrategy
{
    public function createHydrator(Type $type, HydratorFactory $hydratorFactory): ?Hydrator
    {
        if (!$type instanceof Array_) {
            return null;
        }
        $itemType = $type->getValueType();
        $itemHydrator = $hydratorFactory->createHydrator($itemType);
        return new ListHydrator($itemHydrator);
    }
}
