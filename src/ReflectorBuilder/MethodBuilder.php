<?php

namespace Codememory\Reflection\ReflectorBuilder;

use Codememory\Reflection\Enum\MetaKey;
use Codememory\Reflection\Interfaces\ReflectorBuilderInterface;

final class MethodBuilder implements ReflectorBuilderInterface
{
    private ?string $name = null;
    private ?int $modifier = null;
    private bool $isConstruct = false;
    private array $attributes = [];
    private array $parameters = [];

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

    /**
     * @return array<int, ParameterBuilder>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array<int, ParameterBuilder> $parameters
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function fromArray(array $meta): ReflectorBuilderInterface
    {
        $this->setName($meta[MetaKey::NAME->value]);
        $this->setModifier($meta[MetaKey::MODIFIER->value]);
        $this->setIsConstruct($meta[MetaKey::IS_CONSTRUCT->value]);
        $this->setAttributes(array_map(
            static fn (array $data) => (new AttributeBuilder())->fromArray($data),
            $meta[MetaKey::ATTRS->value]
        ));
        $this->setParameters($meta[MetaKey::PARAMS->value]);

        return $this;
    }

    public function toArray(): array
    {
        return [
            MetaKey::NAME->value => $this->getName(),
            MetaKey::MODIFIER->value => $this->getModifier(),
            MetaKey::IS_CONSTRUCT->value => $this->isConstruct(),
            MetaKey::ATTRS->value => array_map(static fn (AttributeBuilder $builder) => $builder->toArray(), $this->getAttributes()),
            MetaKey::PARAMS->value => array_map(static fn (ParameterBuilder $builder) => $builder->toArray(), $this->getParameters()),
        ];
    }
}