<?php

namespace Codememory\Reflection\ReflectorBuilder;

use Codememory\Reflection\Reflectors\AttributeReflector;
use Codememory\Reflection\Reflectors\MethodReflector;
use Codememory\Reflection\Reflectors\PropertyReflector;

final class ClassBuilder
{
    private ?string $name = null;
    private ?string $shortName = null;
    private ?string $namespace = null;
    private bool $isAbstract = false;
    private bool $isFinal = false;
    private bool $isIterable = false;
    private bool $isTrait = false;
    private bool $isInterface = false;
    private bool $isAnonymous = false;

    /**
     * @var array<int, MethodReflector>
     */
    private array $methods = [];

    /**
     * @var array<int, PropertyReflector>
     */
    private array $properties = [];

    /**
     * @var array<int, AttributeReflector>
     */
    private array $attributes = [];
}