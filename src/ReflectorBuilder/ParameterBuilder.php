<?php

namespace Codememory\Reflection\ReflectorBuilder;

use Codememory\Reflection\Enum\MetaKey;
use Codememory\Reflection\Interfaces\ReflectorBuilderInterface;
use Codememory\Reflection\ReflectorBuilder\Traits\TypeTrait;

final class ParameterBuilder implements ReflectorBuilderInterface
{
    use TypeTrait;
    private ?string $name = null;
    private mixed $defaultValue = null;
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

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(mixed $defaultValue): self
    {
        $this->defaultValue = $defaultValue;

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

    public function fromArray(array $meta): ReflectorBuilderInterface
    {
        $this->setName($meta[MetaKey::NAME->value]);
        $this->setType($this->typeToBuilder($meta));
        $this->setDefaultValue($meta[MetaKey::DEFAULT_VALUE->value]);
        $this->setAttributes(array_map(
            static fn (array $data) => (new AttributeBuilder())->fromArray($data),
            $meta[MetaKey::ATTRS->value]
        ));

        return $this;
    }

    public function toArray(): array
    {
        return [
            MetaKey::NAME->value => $this->getName(),
            MetaKey::TYPE->value => $this->typeToArray(),
            MetaKey::DEFAULT_VALUE->value => $this->getDefaultValue(),
            MetaKey::ATTRS->value => array_map(static fn (AttributeBuilder $builder) => $builder->toArray(), $this->getAttributes()),
        ];
    }
}