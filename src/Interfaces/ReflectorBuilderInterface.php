<?php

namespace Codememory\Reflection\Interfaces;

interface ReflectorBuilderInterface
{
    /**
     * @param array<string, mixed> $meta
     */
    public function fromArray(array $meta): self;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}