<?php

namespace Grzesie2k\Hydrator\HydratorStrategy\ObjectHydratorStrategy;

use Grzesie2k\Hydrator\Fixture\Product;
use Grzesie2k\Hydrator\Fixture\User;
use Grzesie2k\Hydrator\Hydrator;
use Grzesie2k\Hydrator\HydratorFactory;
use Grzesie2k\Hydrator\HydratorStrategy;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Null_;
use phpDocumentor\Reflection\Types\Nullable;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\String_;
use PHPUnit\Framework\TestCase;

class ConstructorHydratorStrategyTest extends TestCase
{
    /** @var HydratorStrategy */
    private $hydratorStrategy;

    protected function setUp(): void
    {
        $this->hydratorStrategy = ConstructorHydratorStrategy::createInstance();
    }

    /**
     * @param Type $type
     * @dataProvider unsupportedTypeTestDataProvider
     */
    public function testUnsupportedType(Type $type): void
    {
        $hydratorFactory = $this->createMock(HydratorFactory::class);
        $hydratorFactory->expects($this->never())->method('createHydrator');
        $actual = $this->hydratorStrategy->createHydrator($type, $hydratorFactory);
        $this->assertNull($actual);
    }

    /**
     * @param string $className
     * @param array $parameters
     * @dataProvider hydrationTestDataProvider
     */
    public function testHydration(string $className, array $parameters): void
    {
        $numberOfParameters = \count($parameters);
        $className = '\\' . $className;

        $hydrator = $this->createMock(Hydrator::class);
        $hydratorFactory = $this->createMock(HydratorFactory::class);
        $hydratorFactory
            ->expects($this->exactly($numberOfParameters))
            ->method('createHydrator')
            ->willReturn($hydrator);

        $type = new Object_(new Fqsen($className));
        $actual = $this->hydratorStrategy->createHydrator($type, $hydratorFactory);
        $hydratorMap = array_combine($parameters, array_fill(0, $numberOfParameters, $hydrator));
        $expected = new Hydrator\ConstructorHydrator($className, $hydratorMap);
        $this->assertEquals($expected, $actual);
    }

    public static function unsupportedTypeTestDataProvider(): array
    {
        return [[new String_(), new Null_(), new Nullable(new Object_())]];
    }

    public static function hydrationTestDataProvider(): array
    {
        return [
            [User::class, ['id', 'email', 'products']],
            [Product::class, ['store', 'quantity', 'name', 'description', 'price']],
        ];
    }
}
