<?php

namespace Codememory\Reflection\Interfaces;

interface ReflectorInterface
{
    public function __serialize(): array;

    public function __toString(): string;
}