<?php

namespace Grzesie2k\Hydrator\Hydrator;

use Grzesie2k\Hydrator\Hydrator;

class NullableHydrator implements Hydrator
{
    /** @var Hydrator */
    private $hydrator;

    public function __construct(Hydrator $hydrator)
    {
        $this->hydrator = $hydrator;
    }

    /**
     * @inheritdoc
    */
    public function hydrate($data)
    {
        return null === $data ? null : $this->hydrator->hydrate($data);
    }
}
