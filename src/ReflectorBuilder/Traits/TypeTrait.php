<?php

namespace Codememory\Reflection\ReflectorBuilder\Traits;

use Codememory\Reflection\Enum\MetaKey;
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
    private function typeToBuilder(array $meta): TypeBuilder|array
    {
        $type = $meta[MetaKey::TYPE->value];

        if (is_array($type[array_key_first($type)])) {
            return array_map(
                static fn (array $data) => (new TypeBuilder())->fromArray($data),
                $type
            );
        }

        $builder = new TypeBuilder();

        $builder->fromArray($type);

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