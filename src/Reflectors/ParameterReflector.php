<?php

namespace Codememory\Reflection\Reflectors;

use Codememory\Reflection\Interfaces\ReflectorInterface;
use Codememory\Reflection\ReflectorBuilder\ParameterBuilder;
use Codememory\Reflection\Reflectors\Traits\AttributeTrait;
use Codememory\Reflection\Reflectors\Traits\TypeTrait;

final readonly class ParameterReflector implements ReflectorInterface
{
    use AttributeTrait;
    use TypeTrait;

    public function __construct(
        private ParameterBuilder $builder
    ) {
    }

    public function getName(): string
    {
        return $this->builder->getName();
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