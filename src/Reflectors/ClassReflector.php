<?php

namespace Codememory\Reflection\Reflectors;

use Codememory\Reflection\Interfaces\ReflectorInterface;
use Codememory\Reflection\ReflectorBuilder\ClassBuilder;
use Codememory\Reflection\Reflectors\Traits\AttributeTrait;

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
        return $this->builder->getMethods();
    }

    /**
     * @return array<int, MethodReflector>
     */
    public function getStaticMethods(): array
    {
        return array_filter($this->getMethods(), static fn(MethodReflector $methodReflector) => $methodReflector->isStatic());
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
        return $this->builder->getProperties();
    }

    /**
     * @return array<int, PropertyReflector>
     */
    public function getStaticProperties(): array
    {
        return array_filter($this->getProperties(), static fn(PropertyReflector $propertyReflector) => $propertyReflector->isStatic());
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

    public function __serialize(): array
    {
        return [
            'name' => $this->getName(),
            'short_name' => $this->getShortName(),
            'namespace' => $this->getNamespace(),
            'is_abstract' => $this->isAbstract(),
            'is_final' => $this->isFinal(),
            'is_iterable' => $this->isIterable(),
            'is_trait' => $this->isTrait(),
            'is_interface' => $this->isInterface(),
            'is_anonymous' => $this->isAnonymous(),
            'method' => $this->getMethods(),
            'properties' => $this->getProperties()
        ];
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}