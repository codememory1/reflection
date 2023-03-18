<?php

namespace Codememory\Reflection;

use Codememory\Reflection\ReflectorBuilder\AttributeBuilder;
use Codememory\Reflection\ReflectorBuilder\ClassBuilder;
use Codememory\Reflection\ReflectorBuilder\MethodBuilder;
use Codememory\Reflection\ReflectorBuilder\ParameterBuilder;
use Codememory\Reflection\ReflectorBuilder\PropertyBuilder;
use Codememory\Reflection\ReflectorBuilder\TypeBuilder;
use Codememory\Reflection\Reflectors\ClassReflector;
use Psr\Cache\InvalidArgumentException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use RuntimeException;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Contracts\Cache\ItemInterface;

final class ReflectorManager
{
    public function __construct(
        private readonly AbstractAdapter $cache,
        private readonly bool $isDev = true
    ) {
    }

    /**
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public function getReflector(string $namespace): ClassReflector
    {
        if (!class_exists($namespace)) {
            throw new RuntimeException("Class {$namespace} not found");
        }

        $cacheItem = $this->cache->getItem($namespace);

        if ($this->isDev) {
            return new ClassReflector($this->updateCache($cacheItem, $namespace));
        }

        if (!$cacheItem->isHit()) {
            return new ClassReflector($this->updateCache($cacheItem, $namespace));
        }

        $classBuilder = new ClassBuilder();

        $classBuilder->fromArray($cacheItem->get(), fn () => $this->updateCache($cacheItem, $namespace)->toArray());

        return new ClassReflector($classBuilder);
    }

    /**
     * @throws ReflectionException
     */
    private function updateCache(ItemInterface $cacheItem, string $namespace): ClassBuilder
    {
        $classBuilder = $this->buildClass(new ReflectionClass($namespace));

        $cacheItem->set($classBuilder->toArray());

        $this->cache->save($cacheItem);

        return $classBuilder;
    }

    /**
     * @throws ReflectionException
     */
    private function buildClass(ReflectionClass $reflectionClass): ClassBuilder
    {
        $classBuilder = new ClassBuilder();

        $classBuilder->setName($reflectionClass->getName());
        $classBuilder->setShortName($reflectionClass->getShortName());
        $classBuilder->setNamespace($reflectionClass->getNamespaceName());
        $classBuilder->setIsAbstract($reflectionClass->isAbstract());
        $classBuilder->setIsFinal($reflectionClass->isFinal());
        $classBuilder->setIsIterable($reflectionClass->isIterable());
        $classBuilder->setIsTrait($reflectionClass->isTrait());
        $classBuilder->setIsInterface($reflectionClass->isInterface());
        $classBuilder->setIsAnonymous($reflectionClass->isAnonymous());
        $classBuilder->setMethods($this->buildMethods($reflectionClass->getMethods()));
        $classBuilder->setProperties($this->buildProperties($reflectionClass->getProperties()));
        $classBuilder->setAttributes($this->buildAttributes($reflectionClass->getAttributes()));

        return $classBuilder;
    }

    /**
     * @param array<int, ReflectionMethod> $reflectionMethods
     *
     * @throws ReflectionException
     */
    private function buildMethods(array $reflectionMethods): array
    {
        $methods = [];

        foreach ($reflectionMethods as $reflectionMethod) {
            $builder = new MethodBuilder();

            $builder->setName($reflectionMethod->getName());
            $builder->setModifier($reflectionMethod->getModifiers());
            $builder->setAttributes($this->buildAttributes($reflectionMethod->getAttributes()));
            $builder->setIsConstruct($reflectionMethod->isConstructor());
            $builder->setParameters($this->buildParameters($reflectionMethod->getParameters()));

            $methods[] = $builder;
        }

        return $methods;
    }

    /**
     * @param array<int, ReflectionProperty> $reflectionProperties
     */
    private function buildProperties(array $reflectionProperties): array
    {
        $properties = [];

        foreach ($reflectionProperties as $reflectionProperty) {
            $builder = new PropertyBuilder();

            $builder->setName($reflectionProperty->getName());
            $builder->setType($this->buildNamedType($reflectionProperty->getType()));
            $builder->setModifier($reflectionProperty->getModifiers());
            $builder->setAttributes($this->buildAttributes($reflectionProperty->getAttributes()));
            $builder->setDefaultValue($reflectionProperty->getDefaultValue());

            $properties[] = $builder;
        }

        return $properties;
    }

    private function buildNamedType(ReflectionNamedType $reflectionNamedType): TypeBuilder
    {
        $builder = new TypeBuilder();

        $builder->setName($reflectionNamedType->getName());
        $builder->setAllowNullable($reflectionNamedType->allowsNull());
        $builder->setIsBuiltin($reflectionNamedType->isBuiltin());

        return $builder;
    }

    /**
     * @param array<int, ReflectionParameter> $reflectionParameters
     *
     * @throws ReflectionException
     */
    private function buildParameters(array $reflectionParameters): array
    {
        $parameters = [];

        foreach ($reflectionParameters as $reflectionParameter) {
            $builder = new ParameterBuilder();

            $builder->setName($reflectionParameter->getName());
            $builder->setType($this->buildNamedType($reflectionParameter->getType()));
            $builder->setAttributes($this->buildAttributes($reflectionParameter->getAttributes()));
            $builder->setDefaultValue($reflectionParameter->getDefaultValue());

            $parameters[] = $builder;
        }

        return $parameters;
    }

    /**
     * @param array<int, ReflectionAttribute> $reflectionAttributes
     */
    private function buildAttributes(array $reflectionAttributes): array
    {
        $attributes = [];

        foreach ($reflectionAttributes as $reflectionAttribute) {
            $builder = new AttributeBuilder();

            $builder->setName($reflectionAttribute->getName());
            $builder->setTarget($reflectionAttribute->getTarget());
            $builder->setArguments($reflectionAttribute->getArguments());
            $builder->setIsRepeated($reflectionAttribute->isRepeated());
            $builder->setInstance($reflectionAttribute->newInstance());

            $attributes[] = $builder;
        }

        return $attributes;
    }
}