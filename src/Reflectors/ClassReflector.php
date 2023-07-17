<?php

namespace Codememory\Reflection\Reflectors;

use Codememory\Reflection\Exceptions\NotAvailableInCurrencyPhpVersionException;
use Codememory\Reflection\Interfaces\ReflectorInterface;
use Codememory\Reflection\ReflectorBuilder\ClassBuilder;
use Codememory\Reflection\ReflectorBuilder\ClassConstantBuilder;
use Codememory\Reflection\ReflectorBuilder\MethodBuilder;
use Codememory\Reflection\ReflectorBuilder\PropertyBuilder;
use Codememory\Reflection\Reflectors\Traits\AttributeTrait;
use Exception;
use ReflectionClass;
use ReflectionProperty;

final readonly class ClassReflector implements ReflectorInterface
{
    use AttributeTrait;

    public function __construct(
        private ClassBuilder $builder
    ) {
    }

    public function getName(): ?string
    {
        return $this->builder->getName();
    }

    public function getShortName(): ?string
    {
        return $this->builder->getShortName();
    }

    public function getNamespace(): ?string
    {
        return $this->builder->getNamespace();
    }

    public function getModifiers(): ?int
    {
        return $this->builder->getModifiers();
    }

    public function getParent(): ?self
    {
        $parent = $this->builder->getParent();

        return null === $parent ? null : new self($parent);
    }

    /**
     * @throws Exception
     */
    public function isReadonly(): bool
    {
        if (version_compare(PHP_VERSION, '8.2', '<')) {
            throw new NotAvailableInCurrencyPhpVersionException(8.2, 'isReadonly', self::class);
        }

        return $this->getModifiers() & ReflectionClass::IS_READONLY;
    }

    public function isAbstract(): bool
    {
        return $this->builder->isAbstract();
    }

    public function isFinal(): bool
    {
        return $this->builder->isFinal();
    }

    /**
     * @return bool
     */
    public function isIterable(): bool
    {
        return $this->builder->isIterable();
    }

    public function isTrait(): bool
    {
        return $this->builder->isTrait();
    }

    public function isInterface(): bool
    {
        return $this->builder->isInterface();
    }

    public function isAnonymous(): bool
    {
        return $this->builder->isAnonymous();
    }

    public function isCloneable(): bool
    {
        return $this->builder->isCloneable();
    }

    public function isCustom(): bool
    {
        return $this->builder->isCustom();
    }

    /**
     * @return array<int, MethodReflector>
     */
    public function getMethods(): array
    {
        return array_map(static fn (MethodBuilder $builder) => new MethodReflector($builder), $this->builder->getMethods());
    }

    /**
     * @return array<int, MethodReflector>
     */
    public function getStaticMethods(): array
    {
        return array_filter($this->getMethods(), static fn (MethodReflector $methodReflector) => $methodReflector->isStatic());
    }

    public function hasMethod(string $name): bool
    {
        foreach ($this->getMethods() as $method) {
            if ($method->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<int, string> $excludeParentClasses
     *
     * @return array<int, PropertyReflector>
     */
    public function getPropertiesIncludingParent(array $excludeParentClasses = [], ?int $modifier = null): array
    {
        $properties = [];
        $parent = $this->getParent();

        while (null !== $parent) {
            if (!in_array($parent->getName(), $excludeParentClasses, true)) {
                $properties = array_merge($parent->getProperties($modifier), $properties);
            }

            $parent = $parent->getParent();
        }

        return array_merge($properties, $this->getProperties($modifier));
    }

    /**
     * @return array<int, PropertyReflector>
     */
    public function getProperties(?int $modifier = null): array
    {
        $thisName = $this->getName();
        $properties = array_map(static fn (PropertyBuilder $builder) => new PropertyReflector($builder), $this->builder->getProperties());

        return array_filter($properties, static function(PropertyReflector $property) use ($thisName, $modifier) {
            $belongsToClass = $property->getClass() === $thisName;

            if (null === $modifier) {
                return $belongsToClass;
            }

            return $belongsToClass && $property->getModifier() & $modifier;
        });
    }

    public function getPropertyByName(string $name): ?PropertyReflector
    {
        foreach ($this->getProperties() as $propertyReflector) {
            if ($propertyReflector->getName() === $name) {
                return $propertyReflector;
            }
        }

        return null;
    }

    /**
     * @return array<int, PropertyReflector>
     */
    public function getStaticProperties(): array
    {
        return array_filter($this->getProperties(), static fn (PropertyReflector $propertyReflector) => $propertyReflector->isStatic());
    }

    public function getPrivateProperties(): array
    {
        return array_filter(
            $this->getProperties(),
            static fn (PropertyReflector $propertyReflector) => $propertyReflector->getModifier() & ReflectionProperty::IS_PRIVATE
        );
    }

    public function getPublicProperties(): array
    {
        return array_filter(
            $this->getProperties(),
            static fn (PropertyReflector $propertyReflector) => $propertyReflector->getModifier() & ReflectionProperty::IS_PUBLIC
        );
    }

    public function getProtectedProperties(): array
    {
        return array_filter(
            $this->getProperties(),
            static fn (PropertyReflector $propertyReflector) => $propertyReflector->getModifier() & ReflectionProperty::IS_PROTECTED
        );
    }

    public function hasProperty(string $name): bool
    {
        foreach ($this->getProperties() as $property) {
            if ($property->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<int, ClassReflector>
     */
    public function getTraits(): array
    {
        return array_map(
            static fn (ClassBuilder $classBuilder) => new self($classBuilder),
            $this->builder->getTraits()
        );
    }

    /**
     * @return array<int, ClassReflector>
     */
    public function getInterfaces(): array
    {
        return array_map(
            static fn (ClassBuilder $classBuilder) => new self($classBuilder),
            $this->builder->getInterfaces()
        );
    }

    public function getInterface(string $interface): ?self
    {
        return $this->getInterfaces()[$interface] ?? null;
    }

    public function hasInterface(string $interface): bool
    {
        return array_key_exists($interface, $this->getInterfaces());
    }

    public function getTrait(string $trait): ?self
    {
        return $this->getTraits()[$trait] ?? null;
    }

    public function hasTrait(string $trait): bool
    {
        return array_key_exists($trait, $this->getTraits());
    }

    /**
     * @return array<int, ClassConstantReflector>
     */
    public function getConstants(): array
    {
        return array_map(
            static fn (ClassConstantBuilder $classConstantBuilder) => new ClassConstantReflector($classConstantBuilder),
            $this->builder->getConstants()
        );
    }

    public function getConstant(string $name): ?ClassConstantReflector
    {
        foreach ($this->getConstants() as $constant) {
            if ($constant->getName() === $name) {
                return $constant;
            }
        }

        return null;
    }

    public function hasConstant(string $name): bool
    {
        foreach ($this->getConstants() as $constant) {
            if ($constant->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    public function newInstance(mixed ...$args): object
    {
        if (empty($this->getNamespace())) {
            return new ($this->getName())(...$args);
        }

        return new ($this->getNamespace())(...$args);
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}