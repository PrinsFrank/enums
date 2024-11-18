<?php
declare(strict_types=1);

namespace PrinsFrank\Enums;

use Error;
use PrinsFrank\Enums\Exception\InvalidArgumentException;
use PrinsFrank\Enums\Exception\NameNotFoundException;
use ReflectionAttribute;
use ReflectionEnumUnitCase;

final class UnitEnum
{
    /**
     * @template T of \UnitEnum
     * @param class-string<T> $fqn
     * @throws NameNotFoundException
     * @throws InvalidArgumentException
     * @return T
     */
    public static function fromName(string $fqn, string $name): \UnitEnum
    {
        return self::tryFromName($fqn, $name) ?? throw new NameNotFoundException(sprintf('Name "%s" not found in "%s"', $name, $fqn));
    }

    /**
     * @template T of \UnitEnum
     * @param class-string<T> $fqn
     * @throws InvalidArgumentException
     * @return T|null
     */
    public static function tryFromName(string $fqn, string $keyName): ?\UnitEnum
    {
        if (is_a($fqn, \UnitEnum::class, true) === false) {
            throw new InvalidArgumentException(sprintf('It is only possible to get names of unitEnums, "%s" provided', $fqn));
        }

        if (defined("{$fqn}::{$keyName}") === false) {
            return null;
        }

        try {
            /** @var T $itemValue */
            $itemValue = constant("{$fqn}::{$keyName}");
        } catch (Error) {
            // @codeCoverageIgnoreStart
            return null;
            // @codeCoverageIgnoreEnd
        }

        return $itemValue;
    }

    /**
     * @template T of \UnitEnum
     * @param class-string<T> $fqn
     * @throws InvalidArgumentException
     * @return list<string>
     */
    public static function names(string $fqn): array
    {
        if (is_a($fqn, \UnitEnum::class, true) === false) {
            throw new InvalidArgumentException(sprintf('It is only possible to get names of unitEnums, "%s" provided', $fqn));
        }

        return array_column($fqn::cases(), 'name');
    }

    /**
     * @template T of object
     * @param class-string<T>|null $attributeFQN
     */
    public static function hasCaseAttribute(\UnitEnum $unitEnum, string|null $attributeFQN = null): bool
    {
        return self::getCaseAttributes($unitEnum, $attributeFQN) !== [];
    }

    /**
     * @template T of object
     * @param class-string<T>|null $attributeFQN
     * @return ($attributeFQN is string ? list<T> : array<object>)
     */
    public static function getCaseAttributes(\UnitEnum $unitEnum, string|null $attributeFQN = null): array
    {
        return array_map(
            static function (ReflectionAttribute $reflectionAttribute): object {
                return $reflectionAttribute->newInstance();
            },
            (new ReflectionEnumUnitCase($unitEnum, $unitEnum->name))->getAttributes($attributeFQN)
        );
    }
}
