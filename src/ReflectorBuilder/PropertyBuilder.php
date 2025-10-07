<?php

namespace Codememory\Reflection\ReflectorBuilder;

use Codememory\Reflection\Enum\MetaKey;
use Codememory\Reflection\Interfaces\ReflectorBuilderInterface;
use Codememory\Reflection\ReflectorBuilder\Traits\TypeTrait;

final class PropertyBuilder implements ReflectorBuilderInterface
{
    use TypeTrait;
    private ?string $class = null;
    private ?string $name = null;
    private ?int $modifier = null;
    private mixed $defaultValue = null;
    private array $attributes = [];
    private bool $hasDefaultValue = false;

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

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

    public function getModifier(): ?int
    {
        return $this->modifier;
    }

    public function setModifier(int $modifier): self
    {
        $this->modifier = $modifier;

        return $this;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(mixed $value): self
    {
        $this->defaultValue = $value;

        return $this;
    }

    /**
     * @return array<int, AttributeBuilder>
     */
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

    public function hasDefaultValue(): bool
    {
        return $this->hasDefaultValue;
    }

    public function setHasDefaultValue(bool $hasDefaultValue): self
    {
        $this->hasDefaultValue = $hasDefaultValue;

        return $this;
    }

    public function fromArray(array $meta): ReflectorBuilderInterface
    {
        $this->setClass($meta[MetaKey::NAMESPACE->value]);
        $this->setName($meta[MetaKey::NAME->value]);
        $this->setModifier($meta[MetaKey::MODIFIER->value]);
        $this->setType($this->typeToBuilder($meta));
        $this->setDefaultValue($meta[MetaKey::DEFAULT_VALUE->value]);
        $this->setAttributes(array_map(
            static fn (array $data) => (new AttributeBuilder())->fromArray($data),
            $meta[MetaKey::ATTRS->value]
        ));
        $this->setHasDefaultValue($meta[MetaKey::HAS_DEFAULT_VALUE->value]);

        return $this;
    }

    public function toArray(): array
    {
        return [
            MetaKey::NAMESPACE->value => $this->getClass(),
            MetaKey::NAME->value => $this->getName(),
            MetaKey::MODIFIER->value => $this->getModifier(),
            MetaKey::TYPE->value => $this->typeToArray(),
            MetaKey::DEFAULT_VALUE->value => $this->getDefaultValue(),
            MetaKey::ATTRS->value => array_map(static fn (AttributeBuilder $builder) => $builder->toArray(), $this->getAttributes()),
            MetaKey::HAS_DEFAULT_VALUE->value => $this->hasDefaultValue()
        ];
    }
}