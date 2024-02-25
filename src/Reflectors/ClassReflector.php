<?php

namespace Codememory\Reflection\Reflectors;

use Codememory\Reflection\Exceptions\NotAvailableInCurrencyPhpVersionException;
use Codememory\Reflection\Interfaces\ReflectorInterface;
use Codememory\Reflection\ReflectorBuilder\ClassBuilder;
use Codememory\Reflection\Reflectors\Traits\AttributeTrait;
use Exception;
use ReflectionClass;
use ReflectionProperty;

final class ClassReflector implements ReflectorInterface
{
    use AttributeTrait;

    /**
     * @var array<string, MethodReflector>|bool
     */
    private bool|array $methods = false;

    /**
     * @var array<string, PropertyReflector>|bool
     */
    private bool|array $properties = false;

    /**
     * @var array<string, ClassConstantReflector>|bool
     */
    private bool|array $constants = false;

    /**
     * @var array<string, ClassReflector>|bool
     */
    private bool|array $traits = false;

    /**
     * @var array<string, ClassReflector>|bool
     */
    private bool|array $interfaces = false;

    public function __construct(
        private readonly ClassBuilder $builder
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

    public function getId(): int
    {
        return $this->builder->getId();
    }

    public function getHash(): string
    {
        return $this->builder->getHash();
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
     * @return array<string, MethodReflector>
     */
    public function getMethods(): array
    {
        if (!$this->methods) {
            $this->methods = [];

            foreach ($this->builder->getMethods() as $builder) {
                $this->methods[$builder->getName()] = new MethodReflector($builder);
            }
        }

        return $this->methods;
    }

    public function getMethodByName(string $name): ?MethodReflector
    {
        return $this->getMethods()[$name] ?? null;
    }

    /**
     * @return array<string, MethodReflector>
     */
    public function getStaticMethods(): array
    {
        return array_filter($this->getMethods(), static fn (MethodReflector $methodReflector) => $methodReflector->isStatic());
    }

    public function hasMethod(string $name): bool
    {
        return array_key_exists($name, $this->getMethods());
    }

    /**
     * @param array<int, string> $excludeParentClasses
     *
     * @return array<string, PropertyReflector>
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
     * @return array<string, PropertyReflector>
     */
    public function getProperties(?int $modifier = null): array
    {
        $className = $this->getName();

        if (!$this->properties) {
            $this->properties = [];

            foreach ($this->builder->getProperties() as $builder) {
                $this->properties[$builder->getName()] = new PropertyReflector($builder);
            }
        }

        return array_filter($this->properties, static function(PropertyReflector $property) use ($className, $modifier) {
            $belongsToClass = $property->getClass() === $className;

            if (null === $modifier) {
                return $belongsToClass;
            }

            return $belongsToClass && $property->getModifier() & $modifier;
        });
    }

    public function getPropertyByName(string $name): ?PropertyReflector
    {
        return $this->getProperties()[$name] ?? null;
    }

    /**
     * @return array<string, PropertyReflector>
     */
    public function getStaticProperties(): array
    {
        return array_filter($this->getProperties(), static fn (PropertyReflector $propertyReflector) => $propertyReflector->isStatic());
    }

    /**
     * @return array<string, PropertyReflector>
     */
    public function getPrivateProperties(): array
    {
        return array_filter(
            $this->getProperties(),
            static fn (PropertyReflector $propertyReflector) => $propertyReflector->getModifier() & ReflectionProperty::IS_PRIVATE
        );
    }

    /**
     * @return array<string, PropertyReflector>
     */
    public function getPublicProperties(): array
    {
        return array_filter(
            $this->getProperties(),
            static fn (PropertyReflector $propertyReflector) => $propertyReflector->getModifier() & ReflectionProperty::IS_PUBLIC
        );
    }

    /**
     * @return array<string, PropertyReflector>
     */
    public function getProtectedProperties(): array
    {
        return array_filter(
            $this->getProperties(),
            static fn (PropertyReflector $propertyReflector) => $propertyReflector->getModifier() & ReflectionProperty::IS_PROTECTED
        );
    }

    public function hasProperty(string $name): bool
    {
        return array_key_exists($name, $this->getProperties());
    }

    /**
     * @return array<string, ClassReflector>
     */
    public function getTraits(): array
    {
        if (!$this->traits) {
            $this->traits = [];

            foreach ($this->builder->getTraits() as $builder) {
                $this->traits[$builder->getName()] = new self($builder);
            }
        }

        return $this->traits;
    }

    /**
     * @return array<string, ClassReflector>
     */
    public function getInterfaces(): array
    {
        if (!$this->interfaces) {
            $this->interfaces = [];

            foreach ($this->builder->getInterfaces() as $builder) {
                $this->interfaces[$builder->getName()] = new self($builder);
            }
        }

        return $this->interfaces;
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
     * @return array<string, ClassConstantReflector>
     */
    public function getConstants(): array
    {
        if (!$this->constants) {
            foreach ($this->builder->getConstants() as $builder) {
                $this->constants[$builder->getName()] = new ClassConstantReflector($builder);
            }
        }

        return $this->constants;
    }

    public function getConstant(string $name): ?ClassConstantReflector
    {
        return $this->getConstants()[$name] ?? null;
    }

    public function hasConstant(string $name): bool
    {
        return array_key_exists($name, $this->getConstants());
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