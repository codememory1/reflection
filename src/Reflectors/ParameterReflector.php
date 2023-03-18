<?php

namespace Codememory\Reflection\Reflectors;

use Codememory\Reflection\Interfaces\ReflectorInterface;
use Codememory\Reflection\ReflectorBuilder\ParameterBuilder;
use Codememory\Reflection\Reflectors\Traits\AttributeTrait;

final class ParameterReflector implements ReflectorInterface
{
    use AttributeTrait;

    public function __construct(
        private readonly ParameterBuilder $builder
    ) {
    }

    public function getName(): string
    {
        return $this->builder->getName();
    }

    public function getType(): ?TypeReflector
    {
        return new TypeReflector($this->builder->getType());
    }

    public function getDefaultValue(): mixed
    {
        return $this->builder->getDefaultValue();
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}