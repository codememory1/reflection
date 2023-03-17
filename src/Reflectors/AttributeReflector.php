<?php

namespace Codememory\Reflection\Reflectors;

use Codememory\Reflection\Interfaces\ReflectorInterface;
use Codememory\Reflection\ReflectorBuilder\AttributeBuilder;

final class AttributeReflector implements ReflectorInterface
{
    public function __construct(
        private readonly AttributeBuilder $builder
    ) {
    }

    public function getName(): string
    {
        return $this->builder->getName();
    }

    public function getArguments(): array
    {
        return $this->builder->getArguments();
    }

    public function getTarget(): int
    {
        return $this->builder->getTarget();
    }

    public function isTarget(int $target): bool
    {
        return $this->getTarget() & $target;
    }

    public function isRepeated(): bool
    {
        return $this->builder->isRepeated();
    }

    public function getInstance(): object
    {
        return $this->builder->getInstance();
    }

    public function __serialize(): array
    {
        return [
            'name' => $this->getName(),
            'arguments' => $this->getArguments(),
            'target' => $this->getTarget(),
            'is_repeated' => $this->isRepeated(),
            'instance' => $this->getInstance()
        ];
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}