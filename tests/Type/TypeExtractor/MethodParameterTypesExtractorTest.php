<?php

namespace Grzesie2k\Hydrator\Type\TypeExtractor;

use Grzesie2k\Hydrator\Fixture\Product;
use Grzesie2k\Hydrator\Fixture\Store;
use Grzesie2k\Hydrator\Fixture\User;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Float_;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\Mixed_;
use phpDocumentor\Reflection\Types\Nullable;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\String_;
use PHPUnit\Framework\TestCase;

class MethodParameterTypesExtractorTest extends TestCase
{
    /** @var MethodParameterTypesExtractor */
    private $extractor;

    protected function setUp(): void
    {
        $this->extractor = MethodParameterTypesExtractor::createInstance();
    }

    /**
     * @param \ReflectionMethod $reflectionMethod
     * @param array $expected
     * @dataProvider extractionTestDataProvider
     */
    public function testExtraction(\ReflectionMethod $reflectionMethod, array $expected): void
    {
        $actual = $this->extractor->extract($reflectionMethod);
        $this->assertEquals($expected, $actual);
    }

    public static function extractionTestDataProvider(): array
    {
        return [
            [
                new \ReflectionMethod(Product::class, '__construct'),
                [
                    'store' => new Object_(new Fqsen('\\' . Store::class)),
                    'quantity' => new Integer(),
                    'name' => new String_(),
                    'description' => new Nullable(new String_()),
                    'price' => new Float_()
                ]
            ],
            [
                new \ReflectionMethod(User::class, '__construct'),
                [
                    'id' => new Integer(),
                    'email' => new Mixed_(),
                    'products' => new Array_(new Object_(new Fqsen('\\' . Product::class))),
                ]
            ]
        ];
    }
}
