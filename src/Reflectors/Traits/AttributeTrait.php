<?php

namespace Codememory\Reflection\Reflectors\Traits;

use Codememory\Reflection\ReflectorBuilder\AttributeBuilder;
use Codememory\Reflection\Reflectors\AttributeReflector;

trait AttributeTrait
{
    /**
     * @return array<int, AttributeReflector>
     */
    public function getAttributes(): array
    {
        return array_map(static fn (AttributeBuilder $builder) => new AttributeReflector($builder), $this->builder->getAttributes());
    }

    public function getAttributesByInstance(string $instance): array
    {
        return array_filter($this->getAttributes(), static fn (AttributeReflector $attributeReflector) => $attributeReflector->getInstance() instanceof $instance);
    }

    public function getAttributeByName(string $name): ?AttributeReflector
    {
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->getName() === $name) {
                return $attribute;
            }
        }

        return null;
    }

    public function hasAttribute(string $name): bool
    {
        foreach ($this->getAttributes() as $attribute) {
            if ($attribute->getName() === $name) {
                return true;
            }
        }

        return false;
    }
}