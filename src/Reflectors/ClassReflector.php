<?php

namespace Codememory\Reflection\Reflectors;

use Codememory\Reflection\Interfaces\ReflectorInterface;
use Codememory\Reflection\ReflectorBuilder\ClassBuilder;
use Codememory\Reflection\ReflectorBuilder\MethodBuilder;
use Codememory\Reflection\ReflectorBuilder\PropertyBuilder;
use Codememory\Reflection\Reflectors\Traits\AttributeTrait;
use ReflectionProperty;

final class ClassReflector implements ReflectorInterface
{
    use AttributeTrait;

    public function __construct(
        private readonly ClassBuilder $builder
    ) {
    }

    public function getName(): ?string
    {
        return $this->builder->getName();
    }

    public function getShortName(): ?string
    {
        return $this->builder->getShortName();
    }

    public function getNamespace(): ?string
    {
        return $this->builder->getNamespace();
    }

    public function isAbstract(): bool
    {
        return $this->builder->isAbstract();
    }

    public function isFinal(): bool
    {
        return $this->builder->isFinal();
    }

    /**
     * @return bool
     */
    public function isIterable(): bool
    {
        return $this->builder->isIterable();
    }

    public function isTrait(): bool
    {
        return $this->builder->isTrait();
    }

    public function isInterface(): bool
    {
        return $this->builder->isInterface();
    }

    public function isAnonymous(): bool
    {
        return $this->builder->isAnonymous();
    }

    /**
     * @return array<int, MethodReflector>
     */
    public function getMethods(): array
    {
        return array_map(static fn (MethodBuilder $builder) => new MethodReflector($builder), $this->builder->getMethods());
    }

    /**
     * @return array<int, MethodReflector>
     */
    public function getStaticMethods(): array
    {
        return array_filter($this->getMethods(), static fn (MethodReflector $methodReflector) => $methodReflector->isStatic());
    }

    public function hasMethod(string $name): bool
    {
        foreach ($this->getMethods() as $method) {
            if ($method->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<int, PropertyReflector>
     */
    public function getProperties(): array
    {
        return array_map(static fn (PropertyBuilder $builder) => new PropertyReflector($builder), $this->builder->getProperties());
    }

    public function getProperty(PropertyReflector $property): ?PropertyReflector
    {
        foreach ($this->getProperties() as $propertyReflector) {
            if ($propertyReflector->getName() === $property->getName()) {
                return $propertyReflector;
            }
        }

        return null;
    }

    /**
     * @return array<int, PropertyReflector>
     */
    public function getStaticProperties(): array
    {
        return array_filter($this->getProperties(), static fn (PropertyReflector $propertyReflector) => $propertyReflector->isStatic());
    }

    public function getPrivateProperties(): array
    {
        return array_filter(
            $this->getProperties(),
            static fn (PropertyReflector $propertyReflector) => $propertyReflector->getModifier() & ReflectionProperty::IS_PRIVATE
        );
    }

    public function getPublicProperties(): array
    {
        return array_filter(
            $this->getProperties(),
            static fn (PropertyReflector $propertyReflector) => $propertyReflector->getModifier() & ReflectionProperty::IS_PUBLIC
        );
    }

    public function getProtectedProperties(): array
    {
        return array_filter(
            $this->getProperties(),
            static fn (PropertyReflector $propertyReflector) => $propertyReflector->getModifier() & ReflectionProperty::IS_PROTECTED
        );
    }

    public function hasProperty(string $name): bool
    {
        foreach ($this->getProperties() as $property) {
            if ($property->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}