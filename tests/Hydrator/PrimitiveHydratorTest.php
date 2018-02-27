<?php

namespace Grzesie2k\Hydrator\Hydrator;

use PHPUnit\Framework\TestCase;

class PrimitiveHydratorTest extends TestCase
{
    /**
     * @param $expected
     * @param $data
     * @param string $type
     * @dataProvider hydratorTestDataProvider
     */
    public function testHydrator($expected, $data, string $type): void
    {
        $hydrator = new PrimitiveHydrator($type);
        $actual = $hydrator->hydrate($data);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @param $data
     * @param string $type
     * @dataProvider hydratorExceptionTestDataProvider
     */
    public function testHydratorException($data, string $type): void
    {
        $hydrator = new PrimitiveHydrator($type);
        $this->expectException(\InvalidArgumentException::class);
        $hydrator->hydrate($data);
    }

    public static function hydratorTestDataProvider(): array
    {
        return [
            [1, 1, 'integer'],
            [123, 123, 'integer'],
            ['Lorem ipsum', 'Lorem ipsum', 'string'],
            [2.2, 2.2, 'double', false]
        ];
    }

    public static function hydratorExceptionTestDataProvider(): array
    {
        return [
            [1, 'string'],
            ['Lorem ipsum', 'integer'],
            ['Lorem ipsum', 'double'],
        ];
    }
}
