<?php

namespace Codememory\Reflection\Reflectors;

use Codememory\Reflection\Interfaces\ReflectorInterface;
use Codememory\Reflection\ReflectorBuilder\TypeBuilder;

final class TypeReflector implements ReflectorInterface
{
    public function __construct(
        private readonly TypeBuilder $builder
    ) {
    }

    public function getName(): string
    {
        return $this->builder->getName();
    }

    public function isInt(): bool
    {
        return 'int' === $this->getName();
    }

    public function isString(): bool
    {
        return 'string' === $this->getName();
    }

    public function isFloat(): bool
    {
        return 'float' === $this->getName();
    }

    public function isArray(): bool
    {
        return 'array' === $this->getName();
    }

    public function isObject(): bool
    {
        return 'object' === $this->getName();
    }

    public function isBool(): bool
    {
        return 'bool' === $this->getName();
    }

    public function isType(string $type): bool
    {
        return $this->getName() === $type;
    }

    public function allowNullable(): bool
    {
        return $this->builder->allowNullable();
    }

    public function isBuiltin(): bool
    {
        return $this->builder->isBuiltin();
    }

    public function __serialize(): array
    {
        return [
            'name' => $this->builder->getName(),
            'allow_nullable' => $this->builder->allowNullable(),
            'is_builtin' => $this->builder->isBuiltin()
        ];
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}