<?php

namespace Codememory\Reflection\ReflectorBuilder;

use Codememory\Reflection\Reflectors\AttributeReflector;

final class MethodBuilder
{
    private ?string $name = null;
    private ?int $modifier = null;
    private bool $isConstruct = false;

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

    public function getModifier(): ?int
    {
        return $this->modifier;
    }

    public function setModifier(int $modifier): self
    {
        $this->modifier = $modifier;

        return $this;
    }

    public function isConstruct(): bool
    {
        return $this->isConstruct;
    }

    public function setIsConstruct(bool $is): self
    {
        $this->isConstruct = $is;

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array<int, AttributeReflector> $attributes
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function addAttribute(AttributeReflector $attribute): self
    {
        $this->attributes[] = $attribute;

        return $this;
    }
}