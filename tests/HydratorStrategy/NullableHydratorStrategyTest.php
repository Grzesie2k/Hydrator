<?php

namespace Grzesie2k\Hydrator\HydratorStrategy;

use Grzesie2k\Hydrator\Hydrator;
use Grzesie2k\Hydrator\Hydrator\NullableHydrator;
use Grzesie2k\Hydrator\HydratorFactory;
use Grzesie2k\Hydrator\HydratorStrategy;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\Nullable;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\String_;
use PHPUnit\Framework\TestCase;

class NullableHydratorStrategyTest extends TestCase
{
    /** @var HydratorStrategy */
    private $strategy;

    protected function setUp(): void
    {
        $this->strategy = new NullableHydratorStrategy();
    }

    /**
     * @param Type $baseType
     * @dataProvider validHydratorCreationDataProvider
     */
    public function testValidHydratorCreation(Type $baseType): void
    {
        $hydrator = $this->createMock(Hydrator::class);
        $hydratorFactory = $this->createMock(HydratorFactory::class);
        $hydratorFactory
            ->expects($this->once())
            ->method('createHydrator')
            ->with($baseType)
            ->willReturn($hydrator);
        $actual = $this->strategy->createHydrator(new Nullable($baseType), $hydratorFactory);
        $expected = new NullableHydrator($hydrator);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @param Type $type
     * @dataProvider notSupportedTypeTestDataProvider
     */
    public function testNotSupportedType(Type $type): void
    {
        $hydratorFactory = $this->createMock(HydratorFactory::class);
        $hydratorFactory
            ->expects($this->never())
            ->method('createHydrator');

        $actual = $this->strategy->createHydrator($type, $hydratorFactory);
        $this->assertNull($actual);
    }

    public static function validHydratorCreationDataProvider(): array
    {
        return [[new Integer()], [new Object_()], [new Array_()], [new String_()]];
    }

    public static function notSupportedTypeTestDataProvider(): array
    {
        return [[new Integer()], [new Object_()], [new String_()]];
    }
}
