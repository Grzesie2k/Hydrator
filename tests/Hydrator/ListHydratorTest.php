<?php

namespace Grzesie2k\Hydrator\Hydrator;

use Grzesie2k\Hydrator\Hydrator;
use PHPUnit\Framework\TestCase;

class ListHydratorTest extends TestCase
{
    public function testListHydrator(): void
    {
        $data = [1, 2, 3, 4, 5];
        $hydrator = $this->createMock(Hydrator::class);
        $hydrator
            ->expects($this->exactly(5))
            ->method('hydrate')
            ->willReturnArgument(0);

        $listHydrator = new ListHydrator($hydrator);
        $actual = $listHydrator->hydrate($data);
        $this->assertEquals($data, $actual);
    }

    public function testInvalidData(): void {
        $hydrator = $this->createMock(Hydrator::class);
        $hydrator
            ->expects($this->never())
            ->method('hydrate');

        $listHydrator = new ListHydrator($hydrator);
        $this->expectException(\InvalidArgumentException::class);
        $listHydrator->hydrate(5);
    }
}
