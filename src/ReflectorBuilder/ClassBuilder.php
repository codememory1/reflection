<?php

namespace Codememory\Reflection\ReflectorBuilder;

use Codememory\Reflection\Enum\MetaKey;
use Codememory\Reflection\Interfaces\ReflectorBuilderInterface;

final class ClassBuilder implements ReflectorBuilderInterface
{
    private ?string $name = null;
    private ?string $shortName = null;
    private ?string $namespace = null;
    private ?int $modifiers = null;
    private ?ClassBuilder $parent = null;
    private ?int $id = null;
    private ?string $hash = null;
    private bool $isAbstract = false;
    private bool $isFinal = false;
    private bool $isIterable = false;
    private bool $isTrait = false;
    private bool $isInterface = false;
    private bool $isAnonymous = false;
    private bool $isCloneable = false;
    private array $methods = [];
    private array $properties = [];
    private array $attributes = [];
    private array $constants = [];
    private array $traits = [];
    private array $interfaces = [];
    private bool $isCustom = false;

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

    public function getModifiers(): ?int
    {
        return $this->modifiers;
    }

    public function setModifiers(int $modifiers): self
    {
        $this->modifiers = $modifiers;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

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

    public function isCloneable(): bool
    {
        return $this->isCloneable;
    }

    public function setIsCloneable(bool $isCloneable): self
    {
        $this->isCloneable = $isCloneable;

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

    /**
     * @return array<int, ClassConstantBuilder>
     */
    public function getConstants(): array
    {
        return $this->constants;
    }

    /**
     * @param array<int, ClassConstantBuilder> $constants
     */
    public function setConstants(array $constants): self
    {
        $this->constants = $constants;

        return $this;
    }

    /**
     * @return array<int, ClassBuilder>
     */
    public function getTraits(): array
    {
        return $this->traits;
    }

    /**
     * @param array<int, ClassBuilder> $traits
     */
    public function setTraits(array $traits): self
    {
        $this->traits = $traits;

        return $this;
    }

    /**
     * @return array<int, ClassBuilder>
     */
    public function getInterfaces(): array
    {
        return $this->interfaces;
    }

    /**
     * @param array<int, ClassBuilder> $interfaces
     */
    public function setInterfaces(array $interfaces): self
    {
        $this->interfaces = $interfaces;

        return $this;
    }

    public function isCustom(): bool
    {
        return $this->isCustom;
    }

    public function setIsCustom(bool $custom): self
    {
        $this->isCustom = $custom;

        return $this;
    }

    public function fromArray(array $meta): ReflectorBuilderInterface
    {
        $this->setName($meta[MetaKey::NAME->value]);
        $this->setShortName($meta[MetaKey::SHORT_NAME->value]);
        $this->setNamespace($meta[MetaKey::NAMESPACE->value]);
        $this->setParent(null === $meta[MetaKey::PARENT->value] ? null : $this->fromArray($meta[MetaKey::PARENT->value]));
        $this->setId($meta[MetaKey::ID->value]);
        $this->setHash($meta[MetaKey::HASH->value]);
        $this->setIsFinal($meta[MetaKey::IS_FINAL->value]);
        $this->setIsAbstract($meta[MetaKey::IS_ABSTRACT->value]);
        $this->setIsIterable($meta[MetaKey::IS_ITERABLE->value]);
        $this->setIsTrait($meta[MetaKey::IS_TRAIT->value]);
        $this->setIsInterface($meta[MetaKey::IS_INTERFACE->value]);
        $this->setIsAnonymous($meta[MetaKey::IS_ANONYMOUS->value]);
        $this->setIsCloneable($meta[MetaKey::IS_CLONEABLE->value]);
        $this->setMethods(array_map(
            static fn (array $method) => (new MethodBuilder())->fromArray($method),
            $meta[MetaKey::METHODS->value]
        ));
        $this->setProperties(array_map(
            static fn (array $property) => (new PropertyBuilder())->fromArray($property),
            $meta[MetaKey::PROPS->value]
        ));
        $this->setAttributes(array_map(
            static fn (array $attribute) => (new AttributeBuilder())->fromArray($attribute),
            $meta[MetaKey::ATTRS->value]
        ));
        $this->setConstants(array_map(
            static fn (array $constant) => (new ClassConstantBuilder())->fromArray($constant),
            $meta[MetaKey::CLASS_CONSTANT->value]
        ));
        $this->setTraits(array_map(
            static fn (array $trait) => (new self())->fromArray($trait),
            $meta[MetaKey::TRAITS->value]
        ));
        $this->setInterfaces(array_map(
            static fn (array $trait) => (new self())->fromArray($trait),
            $meta[MetaKey::INTERFACES->value]
        ));
        $this->setIsCustom($meta[MetaKey::CUSTOM->value]);

        return $this;
    }

    public function toArray(): array
    {
        return [
            MetaKey::NAME->value => $this->getName(),
            MetaKey::SHORT_NAME->value => $this->getShortName(),
            MetaKey::NAMESPACE->value => $this->getNamespace(),
            MetaKey::MODIFIER->value => $this->getModifiers(),
            MetaKey::PARENT->value => $this->getParent()?->toArray(),
            MetaKey::ID->value => $this->getId(),
            MetaKey::HASH->value => $this->getHash(),
            MetaKey::IS_ABSTRACT->value => $this->isAbstract(),
            MetaKey::IS_FINAL->value => $this->isFinal(),
            MetaKey::IS_ITERABLE->value => $this->isIterable(),
            MetaKey::IS_TRAIT->value => $this->isTrait(),
            MetaKey::IS_INTERFACE->value => $this->isInterface(),
            MetaKey::IS_ANONYMOUS->value => $this->isAnonymous(),
            MetaKey::IS_CLONEABLE->value => $this->isCloneable(),
            MetaKey::METHODS->value => array_map(static fn (MethodBuilder $builder) => $builder->toArray(), $this->getMethods()),
            MetaKey::PROPS->value => array_map(static fn (PropertyBuilder $builder) => $builder->toArray(), $this->getProperties()),
            MetaKey::ATTRS->value => array_map(static fn (AttributeBuilder $builder) => $builder->toArray(), $this->getAttributes()),
            MetaKey::TRAITS->value => array_map(static fn (ClassBuilder $builder) => $builder->toArray(), $this->getTraits()),
            MetaKey::INTERFACES->value => array_map(static fn (ClassBuilder $builder) => $builder->toArray(), $this->getInterfaces()),
            MetaKey::CUSTOM->value => $this->isCustom(),
            MetaKey::CLASS_CONSTANT->value => array_map(static fn (ClassConstantBuilder $builder) => $builder->toArray(), $this->getConstants()),
        ];
    }
}