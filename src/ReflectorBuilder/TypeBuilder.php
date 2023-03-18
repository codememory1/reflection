<?php

namespace Codememory\Reflection\ReflectorBuilder;

use Codememory\Reflection\Enum\KeyEnum;
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

    public function fromArray(array $meta, callable $updateCacheCallback): ReflectorBuilderInterface
    {
        $expectKeys = [
            KeyEnum::NAME->value,
            KeyEnum::ALLOW_NULLABLE->value,
            KeyEnum::IS_BUILTIN->value
        ];

        if (array_diff($expectKeys, array_keys($meta))) {
            $meta = $updateCacheCallback();
        }

        $this->setName($meta[KeyEnum::NAME->value]);
        $this->setAllowNullable($meta[KeyEnum::ALLOW_NULLABLE->value]);
        $this->setIsBuiltin($meta[KeyEnum::IS_BUILTIN->value]);

        return $this;
    }

    public function toArray(): array
    {
        return [
            KeyEnum::NAME->value => $this->getName(),
            KeyEnum::ALLOW_NULLABLE->value => $this->allowNullable(),
            KeyEnum::IS_BUILTIN->value => $this->isBuiltin()
        ];
    }
}