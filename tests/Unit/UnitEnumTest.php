<?php
declare(strict_types=1);

namespace PrinsFrank\Enums\Tests\Unit;

use PHPUnit\Framework\TestCase;
use PrinsFrank\Enums\Exception\InvalidArgumentException;
use PrinsFrank\Enums\Exception\NameNotFoundException;
use PrinsFrank\Enums\UnitEnum;

/**
 * @coversDefaultClass \PrinsFrank\Enums\UnitEnum
 */
class UnitEnumTest extends TestCase
{
    /**
     * @covers ::tryFromName
     *
     * @throws InvalidArgumentException
     */
    public function testTryFromKey(): void
    {
        static::assertNull(UnitEnum::tryFromName(TestEnum::class, 'BAR'));
        static::assertSame(TestEnum::FOO, UnitEnum::tryFromName(TestEnum::class, 'FOO'));
    }

    /** @covers ::tryFromName */
    public function testTryFromKeyThrowsExceptionOnNonEnumValue(): void
    {
        $testClass = new class () {};

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('It is only possible to get names of unitEnums, "%s" provided', $testClass::class));

        /** @phpstan-ignore-next-line as not everyone has PHPStan to tell them not to pass something else than an Enum FQN */
        UnitEnum::tryFromName($testClass::class, 'foo');
    }

    /**
     * @covers ::fromName
     *
     * @throws InvalidArgumentException
     * @throws NameNotFoundException
     */
    public function testFromKey(): void
    {
        static::assertSame(TestEnum::FOO, UnitEnum::fromName(TestEnum::class, 'FOO'));
    }

    /**
     * @covers ::names
     *
     * @throws InvalidArgumentException
     */
    public function testNames(): void
    {
        static::assertSame(
            UnitEnum::names(TestEnum::class),
            ['FOO', 'FIZ'],
        );
    }

    /** @covers ::names */
    public function testNamesThrowsExceptionOnNonEnumValue(): void
    {
        $testClass = new class () {};

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('It is only possible to get names of unitEnums, "%s" provided', $testClass::class));

        /** @phpstan-ignore-next-line as not everyone has PHPStan to tell them not to pass something else than an Enum FQN */
        UnitEnum::names($testClass::class);
    }

    /**
     * @covers ::fromName
     *
     * @throws InvalidArgumentException
     * @throws NameNotFoundException
     */
    public function testFromKeyThrowsExceptionNonExistingKey(): void
    {
        $this->expectException(NameNotFoundException::class);
        UnitEnum::fromName(TestEnum::class, 'BAR');
    }
}

enum TestEnum
{
    case FOO;
    case FIZ;
}
