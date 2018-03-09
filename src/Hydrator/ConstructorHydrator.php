<?php

namespace Grzesie2k\Hydrator\Hydrator;

use Grzesie2k\Hydrator\Hydrator;

class ConstructorHydrator implements Hydrator
{
    /** @var string */
    private $className;

    /** @var Hydrator[] */
    private $hydratorMap;

    /**
     * ConstructorHydrator constructor.
     * @param string $className
     * @param Hydrator[] $hydratorMap Map of parameterName => Hydrator
     */
    public function __construct(string $className, array $hydratorMap)
    {
        $this->className = $className;
        $this->hydratorMap = $hydratorMap;
    }

    /**
     * @param \stdClass $data
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function hydrate($data)
    {
        if (!$data instanceof \stdClass) {
            throw new \InvalidArgumentException('Expected instance of stdClass');
        }
        $unknownArguments = \array_diff_key((array)$data, $this->hydratorMap);
        if (!empty($unknownArguments)) {
            $unknownArgumentsNames = \array_keys($unknownArguments);
            throw new \InvalidArgumentException(
                'Unknown object properties: ' . implode(', ', $unknownArgumentsNames)
            );
        }
        $arguments = [];
        foreach ($this->hydratorMap as $argumentName => $hydrator) {
            $value = $data->$argumentName ?? null;
            $arguments[] = $hydrator->hydrate($value);
        }

        return new $this->className(...$arguments);
    }
}
