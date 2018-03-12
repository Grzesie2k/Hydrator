<?php

namespace Grzesie2k\Hydrator;

use phpDocumentor\Reflection\TypeResolver;

class HydratorFactory
{
    /** @var TypeResolver */
    private $typeResolver;

    /** @var HydratorStrategy[] */
    private $strategies;

    public function __construct(array $strategies, TypeResolver $typeResolver)
    {
        $this->strategies = $strategies;
        $this->typeResolver = $typeResolver;
    }

    /**
     * @param string $typeString PSR-5 (DocBlock) type, e.g. int, string[] ClassName
     * @return Hydrator
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function createHydrator(string $typeString): Hydrator
    {
        $type = $this->typeResolver->resolve($typeString);

        foreach ($this->strategies as $strategy) {
            $hydrator = $strategy->createHydrator($type, $this);
            if (null !== $hydrator) {
                return $hydrator;
            }
        }

        throw new \RuntimeException("Unsupported type {$type}");
    }
}
