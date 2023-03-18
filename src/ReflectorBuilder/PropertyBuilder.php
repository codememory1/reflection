<?php

namespace Codememory\Reflection\ReflectorBuilder;

use Codememory\Reflection\Enum\KeyEnum;
use Codememory\Reflection\Interfaces\ReflectorBuilderInterface;

final class PropertyBuilder implements ReflectorBuilderInterface
{
    private ?string $name = null;
    private ?int $modifier = null;
    private ?TypeBuilder $type = null;
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

    public function getModifier(): ?int
    {
        return $this->modifier;
    }

    public function setModifier(int $modifier): self
    {
        $this->modifier = $modifier;

        return $this;
    }

    public function getType(): ?TypeBuilder
    {
        return $this->type;
    }

    public function setType(TypeBuilder $type): self
    {
        $this->type = $type;

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

    public function fromArray(array $meta, callable $updateCacheCallback): ReflectorBuilderInterface
    {
        $expectKeys = [
            KeyEnum::NAME->value,
            KeyEnum::MODIFIER->value,
            KeyEnum::TYPE->value,
            KeyEnum::DEFAULT_VALUE->value,
            KeyEnum::ATTRS->value
        ];

        if (array_diff($expectKeys, array_keys($meta))) {
            $meta = $updateCacheCallback();
        }

        $typeBuilder = new TypeBuilder();

        $typeBuilder->fromArray($meta[KeyEnum::TYPE->value], $updateCacheCallback);

        $this->setName($meta[KeyEnum::NAME->value]);
        $this->setModifier($meta[KeyEnum::MODIFIER->value]);
        $this->setType($typeBuilder);
        $this->setDefaultValue($meta[KeyEnum::DEFAULT_VALUE->value]);
        $this->setAttributes(array_map(
            static fn (array $data) => (new AttributeBuilder())->fromArray($data, $updateCacheCallback),
            $meta[KeyEnum::ATTRS->value]
        ));

        return $this;
    }

    public function toArray(): array
    {
        return [
            KeyEnum::NAME->value => $this->getName(),
            KeyEnum::MODIFIER->value => $this->getModifier(),
            KeyEnum::TYPE->value => $this->getType()->toArray(),
            KeyEnum::DEFAULT_VALUE->value => $this->getDefaultValue(),
            KeyEnum::ATTRS->value => array_map(static fn (AttributeBuilder $builder) => $builder->toArray(), $this->getAttributes())
        ];
    }
}