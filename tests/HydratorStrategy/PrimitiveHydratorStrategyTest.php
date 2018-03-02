<?php

namespace Grzesie2k\Hydrator\HydratorStrategy;

use Grzesie2k\Hydrator\Hydrator;
use Grzesie2k\Hydrator\Hydrator\PrimitiveHydrator;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Float_;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\String_;
use PHPUnit\Framework\TestCase;

class PrimitiveHydratorStrategyTest extends TestCase
{
    /** @var PrimitiveHydratorStrategy */
    private $strategy;

    protected function setUp()
    {
        $this->strategy = new PrimitiveHydratorStrategy();
    }

    /**
     * @param Type $type
     * @param Hydrator|null $expected
     * @dataProvider createHydratorTestDataProvider
     */
    public function testCreateHydrator(Type $type, ?Hydrator $expected): void
    {
        $actual = $this->strategy->createHydrator($type);
        $this->assertEquals($expected, $actual);
    }

    public static function createHydratorTestDataProvider(): array
    {
        return [
            [new String_(), new PrimitiveHydrator('string')],
            [new Object_(), null],
            [new Float_(), new PrimitiveHydrator('double')],
            [new Integer(), new PrimitiveHydrator('integer')],
            [new Array_(), null],
        ];
    }

}
