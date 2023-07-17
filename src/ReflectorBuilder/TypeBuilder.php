<?php

namespace Codememory\Reflection\ReflectorBuilder;

use Codememory\Reflection\Enum\MetaKey;
use Codememory\Reflection\Interfaces\ReflectorBuilderInterface;

final class TypeBuilder implements ReflectorBuilderInterface
{
    private ?string $name = null;
    private bool $allowNullable = false;
    private bool $isBuiltin = false;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function allowNullable(): bool
    {
        return $this->allowNullable;
    }

    public function setAllowNullable(bool $is): self
    {
        $this->allowNullable = $is;

        return $this;
    }

    public function isBuiltin(): bool
    {
        return $this->isBuiltin;
    }

    public function setIsBuiltin(bool $is): self
    {
        $this->isBuiltin = $is;

        return $this;
    }

    public function fromArray(array $meta): ReflectorBuilderInterface
    {
        $this->setName($meta[MetaKey::NAME->value]);
        $this->setAllowNullable($meta[MetaKey::ALLOW_NULLABLE->value]);
        $this->setIsBuiltin($meta[MetaKey::IS_BUILTIN->value]);

        return $this;
    }

    public function toArray(): array
    {
        return [
            MetaKey::NAME->value => $this->getName(),
            MetaKey::ALLOW_NULLABLE->value => $this->allowNullable(),
            MetaKey::IS_BUILTIN->value => $this->isBuiltin()
        ];
    }
}