<?php

namespace Grzesie2k\Hydrator\Hydrator;

use Grzesie2k\Hydrator\Fixture\Product;
use Grzesie2k\Hydrator\Hydrator;
use PHPUnit\Framework\TestCase;

class ConstructorHydratorTest extends TestCase
{
    /** @var Hydrator */
    private $hydrator;

    protected function setUp(): void
    {
        $this->hydrator = new ConstructorHydrator(Product::class, [
            'quantity' => $this->createParameterHydratorMock(),
            'name' => $this->createParameterHydratorMock(),
            'description' => $this->createParameterHydratorMock(),
            'price' => $this->createParameterHydratorMock(),
        ]);
    }

    /**
     * @param \stdClass $data
     * @param Product $expected
     * @dataProvider validProductHydrationTestDataProvider
     */
    public function testValidHydration(\stdClass $data, Product $expected): void
    {
        $this->assertEquals($expected, $this->hydrator->hydrate($data));
    }

    /**
     * @param $data
     * @param $exception
     * @dataProvider invalidProductHydrationTestDataProvider
     */
    public function testInvalidProductHydration($data, $exception)
    {
        $this->expectException($exception);
        $this->hydrator->hydrate($data);
    }

    private function createParameterHydratorMock(): Hydrator
    {
        $mock = $this->createMock(Hydrator::class);
        $mock->method('hydrate')->willReturnArgument(0);

        return $mock;
    }

    public static function validProductHydrationTestDataProvider(): array
    {
        return [
            [
                (object)[
                    'quantity' => 0,
                    'description' => 'Product description',
                    'name' => 'Product name',
                    'price' => 3.14,
                ],
                new Product(0, 'Product name', 'Product description', 3.14)
            ],
            [
                (object)[
                    'quantity' => PHP_INT_MAX,
                    'name' => 'Lorem ipsum',
                    'price' => 0.123456789,
                ],
                new Product(PHP_INT_MAX, 'Lorem ipsum', null, 0.123456789)
            ]
        ];
    }

    public static function invalidProductHydrationTestDataProvider(): array
    {
        return [
            [2, \InvalidArgumentException::class],
            ['Product', \InvalidArgumentException::class],
            [
                (object)[
                    'quantity' => 5,
                    'name' => 'Lorem ipsum',
                    'price' => 0.123456789,
                    'premium' => true
                ],
                \InvalidArgumentException::class
            ]
        ];
    }
}
