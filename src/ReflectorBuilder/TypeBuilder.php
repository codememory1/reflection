<?php

namespace Codememory\Reflection\ReflectorBuilder;

final class TypeBuilder
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
}