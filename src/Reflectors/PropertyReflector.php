<?php

namespace Codememory\Reflection\Reflectors;

use Closure;
use Codememory\Reflection\Interfaces\ReflectorInterface;
use Codememory\Reflection\ReflectorBuilder\PropertyBuilder;
use Codememory\Reflection\Reflectors\Traits\AttributeTrait;
use Codememory\Reflection\Reflectors\Traits\TypeTrait;
use ReflectionProperty;

final class PropertyReflector implements ReflectorInterface
{
    use AttributeTrait;
    use TypeTrait;

    public function __construct(
        private readonly PropertyBuilder $builder
    ) {
    }

    public function getClass(): string
    {
        return $this->builder->getClass();
    }

    public function getName(): string
    {
        return $this->builder->getName();
    }

    public function getModifier(): int
    {
        return $this->builder->getModifier();
    }

    public function isPublic(): bool
    {
        return $this->getModifier() & ReflectionProperty::IS_PUBLIC;
    }

    public function isPrivate(): bool
    {
        return $this->getModifier() & ReflectionProperty::IS_PRIVATE;
    }

    public function isProtected(): bool
    {
        return $this->getModifier() & ReflectionProperty::IS_PROTECTED;
    }

    public function isReadOnly(): bool
    {
        return $this->builder->getModifier() & ReflectionProperty::IS_READONLY;
    }

    public function isStatic(): bool
    {
        return $this->builder->getModifier() & ReflectionProperty::IS_STATIC;
    }

    public function getDefaultValue(): mixed
    {
        return $this->builder->getDefaultValue();
    }

    public function getValue(object $object): mixed
    {
        $that = $this;
        $value = null;

        if ($this->isPublic()) {
            return $this->isStatic() ? $object::${$that->getName()} : $object->{$that->getName()};
        }

        (Closure::bind(function(object $object) use (&$value, $that): void {
            $value = $that->isStatic() ? $object::${$that->getName()} : $object->{$that->getName()};
        }, null, $object))($object);

        return $value;
    }

    public function setValue(object $object, mixed $value): self
    {
        $that = $this;
        $propertyName = $this->getName();

        if ($this->isPublic()) {
            $this->isStatic() ? $object::$$propertyName = $value : $object->$propertyName = $value;
        } else {
            (Closure::bind(function(object $object) use ($propertyName, $value, $that): void {
                $that->isStatic() ? $object::$$propertyName = $value : $object->$propertyName = $value;
            }, null, $object))($object);
        }

        return $this;
    }

    public function hasDefaultValue(): bool
    {
        return $this->builder->hasDefaultValue();
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}