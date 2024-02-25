<?php

namespace Codememory\Reflection\Reflectors;

use Codememory\Reflection\Interfaces\ReflectorInterface;
use Codememory\Reflection\ReflectorBuilder\AttributeBuilder;
use Codememory\Reflection\ReflectorBuilder\ClassConstantBuilder;

final class ClassConstantReflector implements ReflectorInterface
{
    public function __construct(
        private readonly ClassConstantBuilder $builder
    ) {
    }

    public function getName(): ?string
    {
        return $this->builder->getName();
    }

    public function getModifiers(): ?int
    {
        return $this->builder->getModifiers();
    }

    public function getValue(): mixed
    {
        return $this->builder->getValue();
    }

    /**
     * @return array<int, AttributeReflector>
     */
    public function getAttributes(): array
    {
        return array_map(static fn (AttributeBuilder $attributeBuilder) => new AttributeReflector($attributeBuilder), $this->builder->getAttributes());
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}