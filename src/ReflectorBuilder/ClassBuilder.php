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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function setNamespace(?string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function isAbstract(): bool
    {
        return $this->isAbstract;
    }

    public function setIsAbstract(bool $isAbstract): self
    {
        $this->isAbstract = $isAbstract;

        return $this;
    }

    public function isFinal(): bool
    {
        return $this->isFinal;
    }

    public function setIsFinal(bool $isFinal): self
    {
        $this->isFinal = $isFinal;

        return $this;
    }

    /**
     * @return bool
     */
    public function isIterable(): bool
    {
        return $this->isIterable;
    }

    public function setIsIterable(bool $isIterable): self
    {
        $this->isIterable = $isIterable;

        return $this;
    }

    public function isTrait(): bool
    {
        return $this->isTrait;
    }

    public function setIsTrait(bool $isTrait): self
    {
        $this->isTrait = $isTrait;

        return $this;
    }

    public function isInterface(): bool
    {
        return $this->isInterface;
    }

    public function setIsInterface(bool $isInterface): self
    {
        $this->isInterface = $isInterface;

        return $this;
    }

    public function isAnonymous(): bool
    {
        return $this->isAnonymous;
    }

    public function setIsAnonymous(bool $isAnonymous): self
    {
        $this->isAnonymous = $isAnonymous;

        return $this;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function setMethods(array $methods): self
    {
        $this->methods = $methods;

        return $this;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setProperties(array $properties): self
    {
        $this->properties = $properties;

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }
}