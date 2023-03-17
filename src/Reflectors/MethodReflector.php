<?php

namespace Codememory\Reflection\Reflectors;

use function call_user_func_array;
use Codememory\Reflection\Interfaces\ReflectorInterface;
use Codememory\Reflection\ReflectorBuilder\MethodBuilder;
use Codememory\Reflection\Reflectors\Traits\AttributeTrait;
use ReflectionMethod;

final class MethodReflector implements ReflectorInterface
{
    use AttributeTrait;

    public function __construct(
        private readonly MethodBuilder $builder
    ) {
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
        return $this->getModifier() & ReflectionMethod::IS_PUBLIC;
    }

    public function isPrivate(): bool
    {
        return $this->getModifier() & ReflectionMethod::IS_PRIVATE;
    }

    public function isProtected(): bool
    {
        return $this->getModifier() & ReflectionMethod::IS_PROTECTED;
    }

    public function isFinal(): bool
    {
        return $this->getModifier() & ReflectionMethod::IS_FINAL;
    }

    public function isAbstract(): bool
    {
        return $this->getModifier() & ReflectionMethod::IS_ABSTRACT;
    }

    public function isStatic(): bool
    {
        return $this->getModifier() & ReflectionMethod::IS_STATIC;
    }

    public function isConstruct(): bool
    {
        return $this->builder->isConstruct();
    }

    public function invoke(object $object, mixed ...$args): self
    {
        $methodName = $this->getName();

        (function(object $object, mixed ...$args) use ($methodName): void {
            call_user_func_array([$object, $methodName], $args);
        })->bindTo($object, $object)->__invoke($object, ...$args);

        return $this;
    }

    public function __serialize(): array
    {
        return [
            'name' => $this->getName(),
            'modifier' => $this->getModifier(),
            'is_construct' => $this->isConstruct(),
            'attributes' => $this->getAttributes()
        ];
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}