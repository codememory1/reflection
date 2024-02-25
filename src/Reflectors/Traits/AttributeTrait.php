<?php

namespace Codememory\Reflection\Reflectors\Traits;

use Codememory\Reflection\Reflectors\AttributeReflector;

trait AttributeTrait
{
    /**
     * @var array<string, AttributeReflector>|bool
     */
    private bool|array $attributes = [];

    /**
     * @return array<string, AttributeReflector>
     */
    public function getAttributes(): array
    {
        if (!$this->attributes) {
            $this->attributes = [];

            foreach ($this->builder->getAttributes() as $builder) {
                $this->attributes[$builder->getName()] = new AttributeReflector($builder);
            }
        }

        return $this->attributes;
    }

    public function getAttributesByInstance(string $instance): array
    {
        return array_filter($this->getAttributes(), static fn (AttributeReflector $attributeReflector) => $attributeReflector->getInstance() instanceof $instance);
    }

    public function getAttributeByName(string $name): ?AttributeReflector
    {
        return $this->getAttributes()[$name] ?? null;
    }

    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->getAttributes());
    }
}