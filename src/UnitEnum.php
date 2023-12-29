<?php
declare(strict_types=1);

namespace PrinsFrank\Enums;

use Error;
use PrinsFrank\Enums\Exception\InvalidArgumentException;
use PrinsFrank\Enums\Exception\NameNotFoundException;

/** @template T of \UnitEnum */
final class UnitEnum
{
    /**
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
     * @param class-string<T> $fqn
     * @throws InvalidArgumentException
     * @return T|null
     */
    public static function tryFromName(string $fqn, string $keyName): ?\UnitEnum
    {
        if (!is_a($fqn, \UnitEnum::class, true)) {
            throw new InvalidArgumentException(sprintf('It is only possible to get names of unitEnums, "%s" provided', $fqn));
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
     * @param class-string<T> $fqn
     * @throws InvalidArgumentException
     * @return array<int, string>
     */
    public static function names(string $fqn): array
    {
        if (!is_a($fqn, \UnitEnum::class, true)) {
            throw new InvalidArgumentException(sprintf('It is only possible to get names of unitEnums, "%s" provided', $fqn));
        }

        return array_column($fqn::cases(), 'name');
    }
}
