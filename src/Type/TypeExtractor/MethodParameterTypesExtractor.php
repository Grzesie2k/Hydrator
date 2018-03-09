<?php

namespace Grzesie2k\Hydrator\Type\TypeExtractor;

use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use phpDocumentor\Reflection\Fqsen;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\TypeResolver;
use phpDocumentor\Reflection\Types\ContextFactory;
use phpDocumentor\Reflection\Types\Mixed_;
use phpDocumentor\Reflection\Types\Nullable;
use phpDocumentor\Reflection\Types\Object_;

class MethodParameterTypesExtractor
{
    /** @var ContextFactory */
    private $contextFactory;

    /** @var DocBlockFactoryInterface */
    private $docBlockFactory;

    /** @var TypeResolver */
    private $typeResolver;

    public static function createInstance(): self
    {
        $contextFactory = new ContextFactory();
        $docBlockFactory = DocBlockFactory::createInstance();
        $typeResolver = new TypeResolver();

        return new self($contextFactory, $docBlockFactory, $typeResolver);
    }

    public function __construct(
        ContextFactory $contextFactory,
        DocBlockFactoryInterface $docBlockFactory,
        TypeResolver $typeResolver
    )
    {
        $this->contextFactory = $contextFactory;
        $this->docBlockFactory = $docBlockFactory;
        $this->typeResolver = $typeResolver;
    }

    /**
     * @param \ReflectionMethod $reflectionMethod
     * @return Type[]
     */
    public function extract(\ReflectionMethod $reflectionMethod): array
    {
        $documentedParameterTypeMap = $this->extractDocumentedParameterTypeMap($reflectionMethod);
        $parameterTypeMap = [];
        foreach ($reflectionMethod->getParameters() as $reflectionParameter) {
            $parameterName = $reflectionParameter->getName();
            $documentedParameterType = $documentedParameterTypeMap[$parameterName] ?? null;
            $parameterType = $this->getParameterType($reflectionParameter, $documentedParameterType);
            $parameterTypeMap[$parameterName] = $parameterType;
        }
        return $parameterTypeMap;
    }

    private function extractDocumentedParameterTypeMap(\ReflectionMethod $reflectionMethod): array
    {
        $docComment = $reflectionMethod->getDocComment();
        if (!$docComment) {
            return [];
        }
        $context = $this->contextFactory->createFromReflector($reflectionMethod);
        $docBlock = $this->docBlockFactory->create($docComment, $context);

        /** @var Param[] $paramTags */
        $paramTags = $docBlock->getTagsByName('param');
        $parameterTypeMap = [];
        foreach ($paramTags as $paramTag) {
            $type = $paramTag->getType();
            $parameterName = $paramTag->getVariableName();
            $parameterTypeMap[$parameterName] = $type;
        }

        return $parameterTypeMap;
    }

    private function getParameterType(\ReflectionParameter $reflectionParameter, ?Type $documentedType): Type
    {
        $classReflection = $reflectionParameter->getClass();
        $typeReflection = $reflectionParameter->getType();
        $nullable = $reflectionParameter->allowsNull();

        if (null !== $classReflection) {
            $fqsn = new Fqsen('\\' . $classReflection->getName());
            $type = new Object_($fqsn);
        } elseif (null !== $typeReflection) {
            $type = $this->typeResolver->resolve($typeReflection->getName());
        } else {
            $nullable = false;
            $type = $documentedType ?? new Mixed_();
        }

        if ($nullable) {
            $type = new Nullable($type);
        }

        return $type;
    }
}
