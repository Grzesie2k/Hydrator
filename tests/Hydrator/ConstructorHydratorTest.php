<?php

namespace Grzesie2k\Hydrator\Hydrator;

use Grzesie2k\Hydrator\Fixture\Product;
use Grzesie2k\Hydrator\Fixture\Store;
use Grzesie2k\Hydrator\Hydrator;
use PHPUnit\Framework\TestCase;

class ConstructorHydratorTest extends TestCase
{
    /** @var Hydrator */
    private $hydrator;

    protected function setUp(): void
    {
        $storeHydrator = $this->createMock(Hydrator::class);
        $storeHydrator
            ->method('hydrate')
            ->willReturnCallback(function ($data) {
                return new Store($data->name);
            });

        $this->hydrator = new ConstructorHydrator(Product::class, [
            'store' => $storeHydrator,
            'quantity' => $this->createMixedHydratorMock(),
            'name' => $this->createMixedHydratorMock(),
            'description' => $this->createMixedHydratorMock(),
            'price' => $this->createMixedHydratorMock(),
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

    private function createMixedHydratorMock(): Hydrator
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
                    'store' => (object)['name' => 'store'],
                    'quantity' => 0,
                    'description' => 'Product description',
                    'name' => 'Product name',
                    'price' => 3.14,
                ],
                new Product(
                    new Store('store'),
                    0, 'Product name',
                    'Product description',
                    3.14
                )
            ],
            [
                (object)[
                    'store' => (object)['name' => 'test'],
                    'quantity' => PHP_INT_MAX,
                    'name' => 'Lorem ipsum',
                    'price' => 0.123456789,
                ],
                new Product(
                    new Store('test'),
                    PHP_INT_MAX,
                    'Lorem ipsum',
                    null,
                    0.123456789
                )
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
