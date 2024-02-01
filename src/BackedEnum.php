<?php
declare(strict_types=1);

namespace PrinsFrank\Enums;

use Error;
use PrinsFrank\Enums\Exception\InvalidArgumentException;
use PrinsFrank\Enums\Exception\NameNotFoundException;
use ReflectionAttribute;
use ReflectionEnumBackedCase;

final class BackedEnum
{
    /**
     * @template T of \BackedEnum
     * @param class-string<T> $fqn
     * @throws NameNotFoundException
     * @throws InvalidArgumentException
     * @return T
     */
    public static function fromName(string $fqn, string $keyName): \BackedEnum
    {
        return self::tryFromName($fqn, $keyName) ?? throw new NameNotFoundException(sprintf('Name "%s" not found in "%s"', $keyName, $fqn));
    }

    /**
     * @template T of \BackedEnum
     * @param class-string<T> $fqn
     * @throws InvalidArgumentException
     * @return T|null
     */
    public static function tryFromName(string $fqn, string $keyName): ?\BackedEnum
    {
        if (is_a($fqn, \BackedEnum::class, true) === false) {
            throw new InvalidArgumentException(sprintf('It is only possible to get names of backedEnums, "%s" provided', $fqn));
        }

        if (!defined("{$fqn}::{$keyName}")) {
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
     * @template T of \BackedEnum
     * @param class-string<T> $fqn
     * @throws InvalidArgumentException
     * @return array<int, string>
     */
    public static function names(string $fqn): array
    {
        if (is_a($fqn, \BackedEnum::class, true) === false) {
            throw new InvalidArgumentException(sprintf('It is only possible to get names of backedEnums, "%s" provided', $fqn));
        }

        return array_column($fqn::cases(), 'name');
    }

    /**
     * @template T of \BackedEnum
     * @param class-string<T> $fqn
     * @throws InvalidArgumentException
     * @return array<int, int|string>
     */
    public static function values(string $fqn): array
    {
        if (is_a($fqn, \BackedEnum::class, true) === false) {
            throw new InvalidArgumentException(sprintf('It is only possible to get values of backedEnums, "%s" provided', $fqn));
        }

        return array_column($fqn::cases(), 'value');
    }

    /**
     * @template T of \BackedEnum
     * @param class-string<T> $fqn
     * @throws InvalidArgumentException
     * @return array<int|string, int|string>
     */
    public static function toArray(string $fqn): array
    {
        if (is_a($fqn, \BackedEnum::class, true) === false) {
            throw new InvalidArgumentException(sprintf('It is only possible to get an array of key/value pairs for backedEnums, "%s" provided', $fqn));
        }

        $cases = $fqn::cases();

        return array_combine(
            array_column($cases, 'name'),
            array_column($cases, 'value')
        );
    }

    /**
     * @template T of object
     * @param class-string<T>|null $attributeFQN
     */
    public static function hasCaseAttribute(\BackedEnum $backedEnum, string|null $attributeFQN = null): bool
    {
        return self::getCaseAttributes($backedEnum, $attributeFQN) !== [];
    }

    /**
     * @template T of object
     * @param class-string<T>|null $attributeFQN
     * @return ($attributeFQN is string ? array<T> : array<object>)
     */
    public static function getCaseAttributes(\BackedEnum $backedEnum, string|null $attributeFQN = null): array
    {
        return array_map(
            static function (ReflectionAttribute $reflectionAttribute): object {
                return $reflectionAttribute->newInstance();
            },
            (new ReflectionEnumBackedCase($backedEnum, $backedEnum->name))->getAttributes($attributeFQN)
        );
    }
}
