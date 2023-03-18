<?php

namespace Codememory\Reflection\ReflectorBuilder;

use Codememory\Reflection\Enum\KeyEnum;
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

    public function fromArray(array $meta, callable $updateCacheCallback): ReflectorBuilderInterface
    {
        $expectKeys = [
            KeyEnum::NAME->value,
            KeyEnum::MODIFIER->value,
            KeyEnum::IS_CONSTRUCT->value,
            KeyEnum::ATTRS->value,
            KeyEnum::PARAMS->value,
        ];

        if (array_diff($expectKeys, array_keys($meta))) {
            $meta = $updateCacheCallback();
        }

        $this->setName($meta[KeyEnum::NAME->value]);
        $this->setModifier($meta[KeyEnum::MODIFIER->value]);
        $this->setIsConstruct($meta[KeyEnum::IS_CONSTRUCT->value]);
        $this->setAttributes(array_map(
            static fn (array $data) => (new AttributeBuilder())->fromArray($data, $updateCacheCallback),
            $meta[KeyEnum::ATTRS->value]
        ));
        $this->setParameters($meta[KeyEnum::PARAMS->value]);

        return $this;
    }

    public function toArray(): array
    {
        return [
            KeyEnum::NAME->value => $this->getName(),
            KeyEnum::MODIFIER->value => $this->getModifier(),
            KeyEnum::IS_CONSTRUCT->value => $this->isConstruct(),
            KeyEnum::ATTRS->value => array_map(static fn (AttributeBuilder $builder) => $builder->toArray(), $this->getAttributes()),
            KeyEnum::PARAMS->value => array_map(static fn (ParameterBuilder $builder) => $builder->toArray(), $this->getParameters()),
        ];
    }
}