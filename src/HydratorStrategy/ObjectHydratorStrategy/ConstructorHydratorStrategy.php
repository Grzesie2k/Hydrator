<?php

namespace Grzesie2k\Hydrator\HydratorStrategy\ObjectHydratorStrategy;

use Grzesie2k\Hydrator\Hydrator;
use Grzesie2k\Hydrator\Hydrator\ConstructorHydrator;
use Grzesie2k\Hydrator\HydratorFactory;
use Grzesie2k\Hydrator\HydratorStrategy;
use Grzesie2k\Hydrator\Type\TypeExtractor\MethodParameterTypesExtractor;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types;

class ConstructorHydratorStrategy implements HydratorStrategy
{
    public static function createInstance(): self
    {
        $typeExtractor = MethodParameterTypesExtractor::createInstance();
        return new self($typeExtractor);
    }

    /** @var MethodParameterTypesExtractor */
    private $typeExtractor;

    public function __construct(MethodParameterTypesExtractor $typeExtractor)
    {
        $this->typeExtractor = $typeExtractor;
    }

    public function createHydrator(Type $type, HydratorFactory $hydratorFactory): ?Hydrator
    {
        if (!$type instanceof Types\Object_) {
            return null;
        }

        $className = (string)$type->getFqsen();
        $classReflection = new \ReflectionClass($className);
        $constructorReflection = $classReflection->getConstructor();
        $parameterTypeMap = $this->typeExtractor->extract($constructorReflection);
        $createHydratorForType = function (string $type) use ($hydratorFactory) {
            return $hydratorFactory->createHydrator($type);
        };
        $parametersHydratorsMap = array_map($createHydratorForType, $parameterTypeMap);

        return new ConstructorHydrator($type, $parametersHydratorsMap);
    }
}
