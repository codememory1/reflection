<?php

namespace Codememory\Reflection\ReflectorBuilder\Traits;

use Codememory\Reflection\Enum\KeyEnum;
use Codememory\Reflection\ReflectorBuilder\TypeBuilder;
use function is_array;

trait TypeTrait
{
    private null|array|TypeBuilder $type = null;

    /**
     * @return null|array<int, TypeBuilder>|TypeBuilder
     */
    public function getType(): null|array|TypeBuilder
    {
        return $this->type;
    }

    /**
     * @param array<int, TypeBuilder>|TypeBuilder $type
     */
    public function setType(TypeBuilder|array $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array<int, TypeBuilder>|TypeBuilder
     */
    private function typeToBuilder(array $meta, $updateCacheCallback): TypeBuilder|array
    {
        $type = $meta[KeyEnum::TYPE->value];

        if (is_array($type[array_key_first($type)])) {
            return array_map(
                static fn (array $data) => (new TypeBuilder())->fromArray($type, $updateCacheCallback),
                $type
            );
        }

        $builder = new TypeBuilder();

        $builder->fromArray($type, $updateCacheCallback);

        return $builder;
    }

    private function typeToArray(): array
    {
        if (is_array($this->getType())) {
            return array_map(static fn (TypeBuilder $builder) => $builder->toArray(), $this->getType());
        }

        return $this->getType()->toArray();
    }
}