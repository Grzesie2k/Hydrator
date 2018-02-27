<?php

namespace Grzesie2k\Hydrator\Hydrator;

use Grzesie2k\Hydrator\Hydrator;

class PrimitiveHydrator implements Hydrator
{
    /** @var string */
    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @inheritdoc
     */
    public function hydrate($data)
    {
        $actualType = \gettype($data);
        if ($actualType !== $this->type) {
            throw new \InvalidArgumentException("Expected {$this->type}, got {$actualType}");
        }

        return $data;
    }
}
