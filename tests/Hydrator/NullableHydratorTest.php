<?php

namespace Grzesie2k\Hydrator\Hydrator;

use Grzesie2k\Hydrator\Hydrator;
use PHPUnit\Framework\TestCase;

class NullableHydratorTest extends TestCase
{
    public function testValueHydrate(): void
    {
        $data = 5;
        $hydrator = $this->createMock(Hydrator::class);
        $hydrator
            ->expects($this->once())
            ->method('hydrate')
            ->willReturn($data)
            ->with($this->equalTo($data));
        $nullableHydrator = new NullableHydrator($hydrator);
        $actual = $nullableHydrator->hydrate($data);

        $this->assertEquals($data, $actual);
    }

    public function testNullHydrate(): void
    {
        $hydrator = $this->createMock(Hydrator::class);
        $hydrator
            ->expects($this->never())
            ->method('hydrate');
        $nullableHydrator = new NullableHydrator($hydrator);

        $nullableHydrator->hydrate(null);
    }
}
