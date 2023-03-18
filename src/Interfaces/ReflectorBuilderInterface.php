<?php

namespace Codememory\Reflection\Interfaces;

interface ReflectorBuilderInterface
{
    public function fromArray(array $meta, callable $updateCacheCallback): self;

    public function toArray(): array;
}