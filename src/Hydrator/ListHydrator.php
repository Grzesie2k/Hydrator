<?php

namespace Grzesie2k\Hydrator\Hydrator;

use Grzesie2k\Hydrator\Hydrator;

class ListHydrator implements Hydrator
{
    /** @var Hydrator */
    private $itemHydrator;

    public function __construct(Hydrator $itemHydrator)
    {
        $this->itemHydrator = $itemHydrator;
    }

    /**
     * @param array $data
     * @return array
     * @throws \InvalidArgumentException
     */
    public function hydrate($data): array
    {
        $actualType = \gettype($data);
        if ('array' !== $actualType) {
            throw new \InvalidArgumentException("Expected array, got {$actualType}");
        }

        return array_map([$this->itemHydrator, 'hydrate'], $data);
    }
}
