<?php

namespace Codememory\Reflection\ReflectorBuilder;

use Codememory\Reflection\Reflectors\AttributeReflector;
use Codememory\Reflection\Reflectors\TypeReflector;

final class PropertyBuilder
{
    private ?string $name = null;
    private ?int $modifier = null;
    private ?TypeReflector $type = null;
    private mixed $defaultValue = null;

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

    public function getType(): ?TypeReflector
    {
        return $this->type;
    }

    public function setType(TypeReflector $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(bool $value): self
    {
        $this->defaultValue = $value;

        return $this;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(): array
    {
        return $this->attributes;
    }

    public function addAttribute(AttributeReflector $attribute): self
    {
        $this->attributes[] = $attribute;

        return $this;
    }
}