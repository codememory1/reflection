<?php

namespace Codememory\Reflection\Reflectors\Traits;

use Codememory\Reflection\ReflectorBuilder\TypeBuilder;
use Codememory\Reflection\Reflectors\TypeReflector;
use function is_array;

trait TypeTrait
{
    /**
     * @return array<int, TypeReflector>|TypeReflector
     */
    public function getType(): TypeReflector|array
    {
        if (is_array($this->builder->getType())) {
            return array_map(static fn (TypeBuilder $builder) => new TypeReflector($builder), $this->builder->getType());
        }

        return new TypeReflector($this->builder->getType());
    }
}