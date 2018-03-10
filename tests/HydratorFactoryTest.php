<?php

namespace Grzesie2k\Hydrator;

use phpDocumentor\Reflection\TypeResolver;
use PHPUnit\Framework\TestCase;

class HydratorFactoryTest extends TestCase
{
    /** @var TypeResolver */
    private $typeResolver;

    protected function setUp(): void
    {
        $this->typeResolver = new TypeResolver();
    }

    public function testPrimitiveHydration(): void
    {
        $expected = $this->createMock(Hydrator::class);

        $notMatchingStrategy = $this->createMock(HydratorStrategy::class);
        $notMatchingStrategy
            ->expects($this->exactly(3))
            ->method('createHydrator')
            ->willReturn(null);

        $matchingStrategy = $this->createMock(HydratorStrategy::class);
        $matchingStrategy
            ->expects($this->once())
            ->method('createHydrator')
            ->willReturn($expected);

        $unknownStrategy = $this->createMock(HydratorStrategy::class);
        $unknownStrategy
            ->expects($this->never())
            ->method('createHydrator');

        $strategies = [
            $notMatchingStrategy, $notMatchingStrategy, $notMatchingStrategy,
            $matchingStrategy,
            $unknownStrategy, $unknownStrategy
        ];
        $hydratorFactory = new HydratorFactory($strategies, $this->typeResolver);
        $hydrator = $hydratorFactory->createHydrator('int');
        $this->assertEquals($expected, $hydrator);
    }

    public function testMissingStrategyException(): void
    {
        $hydratorFactory = new HydratorFactory([], $this->typeResolver);
        $this->expectException(\RuntimeException::class);
        $hydratorFactory->createHydrator('int');
    }
}
