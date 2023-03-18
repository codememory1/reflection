<?php

namespace Codememory\Reflection\ReflectorBuilder;

use Codememory\Reflection\Enum\KeyEnum;
use Codememory\Reflection\Interfaces\ReflectorBuilderInterface;

final class AttributeBuilder implements ReflectorBuilderInterface
{
    private ?string $name = null;
    private array $arguments = [];
    private ?int $target = null;
    private bool $isRepeated = false;
    private ?object $instance = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function setArguments(array $args): self
    {
        $this->arguments = $args;

        return $this;
    }

    public function getTarget(): ?int
    {
        return $this->target;
    }

    public function setTarget(int $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function isRepeated(): bool
    {
        return $this->isRepeated;
    }

    public function setIsRepeated(bool $isRepeated): self
    {
        $this->isRepeated = $isRepeated;

        return $this;
    }

    public function getInstance(): ?object
    {
        return $this->instance;
    }

    public function setInstance(object $instance): self
    {
        $this->instance = $instance;

        return $this;
    }

    public function fromArray(array $meta, callable $updateCacheCallback): ReflectorBuilderInterface
    {
        $expectKeys = [
            KeyEnum::NAME->value,
            KeyEnum::ARGUMENTS->value,
            KeyEnum::TARGET->value,
            KeyEnum::IS_REPEATED_KEY->value,
            KeyEnum::INSTANCE->value,
        ];

        if (array_diff($expectKeys, array_keys($meta))) {
            $meta = $updateCacheCallback();
        }

        $this->setName($meta[KeyEnum::NAME->value]);
        $this->setArguments($meta[KeyEnum::ARGUMENTS->value]);
        $this->setTarget($meta[KeyEnum::TARGET->value]);
        $this->setIsRepeated($meta[KeyEnum::IS_REPEATED_KEY->value]);
        $this->setInstance($meta[KeyEnum::INSTANCE->value]);

        return $this;
    }

    public function toArray(): array
    {
        return [
            KeyEnum::NAME->value => $this->getName(),
            KeyEnum::ARGUMENTS->value => $this->getArguments(),
            KeyEnum::TARGET->value => $this->getTarget(),
            KeyEnum::IS_REPEATED_KEY->value => $this->isRepeated,
            KeyEnum::INSTANCE->value => $this->getInstance()
        ];
    }
}