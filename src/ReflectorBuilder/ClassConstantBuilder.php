<?php

namespace Codememory\Reflection\ReflectorBuilder;

use Codememory\Reflection\Enum\MetaKey;
use Codememory\Reflection\Interfaces\ReflectorBuilderInterface;

final class ClassConstantBuilder implements ReflectorBuilderInterface
{
    /**
     * @var array<int, AttributeBuilder>
     */
    private array $attributes = [];
    private ?string $name = null;
    private ?int $modifiers = null;
    private mixed $value = null;

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array<int, AttributeBuilder> $attributes
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getModifiers(): ?int
    {
        return $this->modifiers;
    }

    public function setModifiers(int $modifiers): self
    {
        $this->modifiers = $modifiers;

        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function fromArray(array $meta): ReflectorBuilderInterface
    {
        $this->setAttributes(array_map(static fn (array $attribute) => (new AttributeBuilder())->fromArray($attribute), $meta[MetaKey::ATTRS->value]));
        $this->setModifiers($meta[MetaKey::MODIFIER->value]);
        $this->setName($meta[MetaKey::NAME->value]);
        $this->setValue($meta[MetaKey::VALUE->value]);

        return $this;
    }

    public function toArray(): array
    {
        return [
            MetaKey::ATTRS->value => array_map(static fn (AttributeBuilder $attributeBuilder) => $attributeBuilder->toArray(), $this->getAttributes()),
            MetaKey::NAME->value => $this->getName(),
            MetaKey::MODIFIER->value => $this->getModifiers(),
            MetaKey::VALUE->value => $this->getValue()
        ];
    }
}