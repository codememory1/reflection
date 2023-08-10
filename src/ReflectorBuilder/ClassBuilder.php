<?php

namespace Codememory\Reflection\ReflectorBuilder;

use Codememory\Reflection\Enum\KeyEnum;
use Codememory\Reflection\Interfaces\ReflectorBuilderInterface;

final class ClassBuilder implements ReflectorBuilderInterface
{
    private ?string $name = null;
    private ?string $shortName = null;
    private ?string $namespace = null;
    private ?ClassBuilder $parent = null;
    private bool $isAbstract = false;
    private bool $isFinal = false;
    private bool $isIterable = false;
    private bool $isTrait = false;
    private bool $isInterface = false;
    private bool $isAnonymous = false;
    private array $methods = [];
    private array $properties = [];
    private array $attributes = [];

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function setNamespace(?string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function getParent(): ?ClassBuilder
    {
        return $this->parent;
    }

    public function setParent(?ClassBuilder $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function isAbstract(): bool
    {
        return $this->isAbstract;
    }

    public function setIsAbstract(bool $isAbstract): self
    {
        $this->isAbstract = $isAbstract;

        return $this;
    }

    public function isFinal(): bool
    {
        return $this->isFinal;
    }

    public function setIsFinal(bool $isFinal): self
    {
        $this->isFinal = $isFinal;

        return $this;
    }

    /**
     * @return bool
     */
    public function isIterable(): bool
    {
        return $this->isIterable;
    }

    public function setIsIterable(bool $isIterable): self
    {
        $this->isIterable = $isIterable;

        return $this;
    }

    public function isTrait(): bool
    {
        return $this->isTrait;
    }

    public function setIsTrait(bool $isTrait): self
    {
        $this->isTrait = $isTrait;

        return $this;
    }

    public function isInterface(): bool
    {
        return $this->isInterface;
    }

    public function setIsInterface(bool $isInterface): self
    {
        $this->isInterface = $isInterface;

        return $this;
    }

    public function isAnonymous(): bool
    {
        return $this->isAnonymous;
    }

    public function setIsAnonymous(bool $isAnonymous): self
    {
        $this->isAnonymous = $isAnonymous;

        return $this;
    }

    /**
     * @return array<int, MethodBuilder>
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param array<int, MethodBuilder> $methods
     */
    public function setMethods(array $methods): self
    {
        $this->methods = $methods;

        return $this;
    }

    /**
     * @return array<int, PropertyBuilder>
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param array<int, PropertyBuilder> $properties
     */
    public function setProperties(array $properties): self
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return array<int, AttributeBuilder>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array<int, AttributeBuilder> $attributes
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function fromArray(array $meta, callable $updateCacheCallback): ReflectorBuilderInterface
    {
        $expectKeys = [
            KeyEnum::NAME->value,
            KeyEnum::SHORT_NAME->value,
            KeyEnum::NAMESPACE->value,
            KeyEnum::IS_ABSTRACT->value,
            KeyEnum::IS_FINAL->value,
            KeyEnum::IS_ITERABLE->value,
            KeyEnum::IS_TRAIT->value,
            KeyEnum::IS_INTERFACE->value,
            KeyEnum::IS_ANONYMOUS->value,
            KeyEnum::METHODS->value,
            KeyEnum::PROPS->value,
            KeyEnum::ATTRS->value,
            KeyEnum::PARENT->value,
        ];

        if (array_diff($expectKeys, array_keys($meta))) {
            $meta = $updateCacheCallback();
        }

        $parent = null;

        if (null !== $meta[KeyEnum::PARENT->value] && [] !== $meta[KeyEnum::PARENT->value]) {
            $parent = new self();

            $parent->fromArray($meta[KeyEnum::PARENT->value], $updateCacheCallback);
        }
        
        $this->setName($meta[KeyEnum::NAME->value]);
        $this->setShortName($meta[KeyEnum::SHORT_NAME->value]);
        $this->setNamespace($meta[KeyEnum::NAMESPACE->value]);
        $this->setParent($parent);
        $this->setIsFinal($meta[KeyEnum::IS_FINAL->value]);
        $this->setIsAbstract($meta[KeyEnum::IS_ABSTRACT->value]);
        $this->setIsIterable($meta[KeyEnum::IS_ITERABLE->value]);
        $this->setIsTrait($meta[KeyEnum::IS_TRAIT->value]);
        $this->setIsInterface($meta[KeyEnum::IS_INTERFACE->value]);
        $this->setIsAnonymous($meta[KeyEnum::IS_ANONYMOUS->value]);
        $this->setMethods(array_map(
            static fn (array $data) => (new MethodBuilder())->fromArray($data, $updateCacheCallback),
            $meta[KeyEnum::METHODS->value]
        ));
        $this->setProperties(array_map(
            static fn (array $data) => (new PropertyBuilder())->fromArray($data, $updateCacheCallback),
            $meta[KeyEnum::PROPS->value]
        ));
        $this->setAttributes(array_map(
            static fn (array $data) => (new AttributeBuilder())->fromArray($data, $updateCacheCallback),
            $meta[KeyEnum::ATTRS->value]
        ));

        return $this;
    }

    public function toArray(): array
    {
        return [
            KeyEnum::NAME->value => $this->getName(),
            KeyEnum::SHORT_NAME->value => $this->getShortName(),
            KeyEnum::NAMESPACE->value => $this->getNamespace(),
            KeyEnum::PARENT->value => $this->getParent()?->toArray(),
            KeyEnum::IS_ABSTRACT->value => $this->isAbstract(),
            KeyEnum::IS_FINAL->value => $this->isFinal(),
            KeyEnum::IS_ITERABLE->value => $this->isIterable(),
            KeyEnum::IS_TRAIT->value => $this->isTrait(),
            KeyEnum::IS_INTERFACE->value => $this->isInterface(),
            KeyEnum::IS_ANONYMOUS->value => $this->isAnonymous(),
            KeyEnum::METHODS->value => array_map(static fn (MethodBuilder $builder) => $builder->toArray(), $this->getMethods()),
            KeyEnum::PROPS->value => array_map(static fn (PropertyBuilder $builder) => $builder->toArray(), $this->getProperties()),
            KeyEnum::ATTRS->value => array_map(static fn (AttributeBuilder $builder) => $builder->toArray(), $this->getAttributes())
        ];
    }
}