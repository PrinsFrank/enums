<?php
declare(strict_types=1);

namespace PrinsFrank\Enums;

use Error;
use PrinsFrank\Enums\Exception\InvalidArgumentException;
use PrinsFrank\Enums\Exception\NameNotFoundException;

/** @template T of \BackedEnum */
final class BackedEnum
{
    /**
     * @param class-string<T> $fqn
     * @throws NameNotFoundException
     * @throws InvalidArgumentException
     * @return T
     */
    public static function fromName(string $fqn, string $keyName): \BackedEnum
    {
        return self::tryFromName($fqn, $keyName) ?? throw new NameNotFoundException('Name "' . $keyName . '" not found in "' . $fqn . '"');
    }

    /**
     * @param class-string<T> $fqn
     * @throws InvalidArgumentException
     * @return T|null
     */
    public static function tryFromName(string $fqn, string $keyName): ?\BackedEnum
    {
        if (is_a($fqn, \BackedEnum::class, true) === false) {
            throw new InvalidArgumentException('It is only possible to get names of backedEnums, "' . $fqn . '" provided');
        }

        if (!defined("$fqn::$keyName")) {
            return null;
        }

        try {
            /** @var T $itemValue */
            $itemValue = constant("$fqn::$keyName");
        } catch (Error) {
            // @codeCoverageIgnoreStart
            return null;
            // @codeCoverageIgnoreEnd
        }

        return $itemValue;
    }

    /**
     * @param class-string<T> $fqn
     * @throws InvalidArgumentException
     * @return array<int, string>
     */
    public static function names(string $fqn): array
    {
        if (is_a($fqn, \BackedEnum::class, true) === false) {
            throw new InvalidArgumentException('It is only possible to get names of backedEnums, "' . $fqn . '" provided');
        }

        return array_column($fqn::cases(), 'name');
    }

    /**
     * @param class-string<T> $fqn
     * @throws InvalidArgumentException
     * @return array<int, int|string>
     */
    public static function values(string $fqn): array
    {
        if (is_a($fqn, \BackedEnum::class, true) === false) {
            throw new InvalidArgumentException('It is only possible to get values of backedEnums, "' . $fqn . '" provided');
        }

        return array_column($fqn::cases(), 'value');
    }

    /**
     * @param class-string<T> $fqn
     * @throws InvalidArgumentException
     * @return array<int|string, int|string>
     */
    public static function toArray(string $fqn): array
    {
        if (is_a($fqn, \BackedEnum::class, true) === false) {
            throw new InvalidArgumentException('It is only possible to get an array of key/value pairs for backedEnums, "' . $fqn . '" provided');
        }

        $cases = $fqn::cases();

        return array_combine(
            array_column($cases, 'name'),
            array_column($cases, 'value')
        );
    }
}
