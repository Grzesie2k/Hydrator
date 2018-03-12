<?php

namespace Grzesie2k\Hydrator\HydratorStrategy;

use Grzesie2k\Hydrator\Hydrator;
use Grzesie2k\Hydrator\Hydrator\ListHydrator;
use Grzesie2k\Hydrator\HydratorFactory;
use Grzesie2k\Hydrator\HydratorStrategy;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Float_;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\String_;
use PHPUnit\Framework\TestCase;

class ListHydratorStrategyTest extends TestCase
{
    /** @var  HydratorStrategy */
    private $strategy;

    protected function setUp(): void
    {
        $this->strategy = new ListHydratorStrategy();
    }

    /**
     * @param Type $itemType
     * @dataProvider validHydratorCreationDataProvider
     */
    public function testValidHydratorCreation(Type $itemType): void
    {
        $itemHydrator = $this->createMock(Hydrator::class);
        $hydratorFactory = $this->createMock(HydratorFactory::class);
        $hydratorFactory
            ->expects($this->once())
            ->method('createHydrator')
            ->with($itemType)
            ->willReturn($itemHydrator);
        $type = new Array_($itemType);
        $actual = $this->strategy->createHydrator($type, $hydratorFactory);
        $expected = new ListHydrator($itemHydrator);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @param Type $type
     * @dataProvider unsupportedTypeTestDataProvider
     */
    public function testUnsupportedType(Type $type): void
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
        return [[new String_()], [new Object_()], [new Float_()], [new Array_()]];
    }

    public static function unsupportedTypeTestDataProvider(): array
    {
        return [[new String_(), new Object_(), new Float_()]];
    }
}
