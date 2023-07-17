<?php

namespace Codememory\Reflection\ReflectorBuilder;

use Codememory\Reflection\Enum\MetaKey;
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

    public function fromArray(array $meta): ReflectorBuilderInterface
    {
        $this->setName($meta[MetaKey::NAME->value]);
        $this->setArguments($meta[MetaKey::ARGUMENTS->value]);
        $this->setTarget($meta[MetaKey::TARGET->value]);
        $this->setIsRepeated($meta[MetaKey::IS_REPEATED_KEY->value]);
        $this->setInstance($meta[MetaKey::INSTANCE->value]);

        return $this;
    }

    public function toArray(): array
    {
        return [
            MetaKey::NAME->value => $this->getName(),
            MetaKey::ARGUMENTS->value => $this->getArguments(),
            MetaKey::TARGET->value => $this->getTarget(),
            MetaKey::IS_REPEATED_KEY->value => $this->isRepeated,
            MetaKey::INSTANCE->value => $this->getInstance()
        ];
    }
}