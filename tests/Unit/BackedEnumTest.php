<?php
declare(strict_types=1);

namespace PrinsFrank\Enums\Tests\Unit;

use Attribute;
use PHPUnit\Framework\TestCase;
use PrinsFrank\Enums\BackedEnum;
use PrinsFrank\Enums\Exception\InvalidArgumentException;
use PrinsFrank\Enums\Exception\NameNotFoundException;

/**
 * @coversDefaultClass \PrinsFrank\Enums\BackedEnum
 */
class BackedEnumTest extends TestCase
{
    /**
     * @covers ::tryFromName
     *
     * @throws InvalidArgumentException
     */
    public function testTryFromKey(): void
    {
        static::assertNull(BackedEnum::tryFromName(TestEnumBackedByString::class, 'BAR'));
        static::assertSame(TestEnumBackedByString::FOO, BackedEnum::tryFromName(TestEnumBackedByString::class, 'FOO'));

        static::assertNull(BackedEnum::tryFromName(TestEnumBackedByInt::class, 'BAR'));
        static::assertSame(TestEnumBackedByInt::FOO, BackedEnum::tryFromName(TestEnumBackedByInt::class, 'FOO'));
    }

    /** @covers ::tryFromName */
    public function testTryFromKeyThrowsExceptionOnNonEnumValue(): void
    {
        $testClass = new class () {};

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('It is only possible to get names of backedEnums, "%s" provided', $testClass::class));

        /** @phpstan-ignore-next-line as not everyone has PHPStan to tell them not to pass something else than an Enum FQN */
        BackedEnum::tryFromName($testClass::class, 'foo');
    }

    /**
     * @covers ::fromName
     *
     * @throws InvalidArgumentException
     * @throws NameNotFoundException
     */
    public function testFromKey(): void
    {
        static::assertSame(TestEnumBackedByString::FOO, BackedEnum::fromName(TestEnumBackedByString::class, 'FOO'));
        static::assertSame(TestEnumBackedByInt::FOO, BackedEnum::fromName(TestEnumBackedByInt::class, 'FOO'));
    }

    /**
     * @covers ::names
     *
     * @throws InvalidArgumentException
     */
    public function testNames(): void
    {
        static::assertSame(
            BackedEnum::names(TestEnumBackedByString::class),
            ['FOO', 'FIZ'],
        );
        static::assertSame(
            BackedEnum::names(TestEnumBackedByInt::class),
            ['FOO', 'FIZ'],
        );
    }

    /** @covers ::names */
    public function testNamesThrowsExceptionOnNonEnumValue(): void
    {
        $testClass = new class () {};

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('It is only possible to get names of backedEnums, "%s" provided', $testClass::class));

        /** @phpstan-ignore-next-line as not everyone has PHPStan to tell them not to pass something else than an Enum FQN */
        BackedEnum::names($testClass::class);
    }

    /**
     * @covers ::values
     *
     * @throws InvalidArgumentException
     */
    public function testValues(): void
    {
        static::assertSame(
            BackedEnum::values(TestEnumBackedByString::class),
            ['foo', 'fiz'],
        );
        static::assertSame(
            BackedEnum::values(TestEnumBackedByInt::class),
            [42, 43],
        );
    }

    /** @covers ::values */
    public function testValuesThrowsExceptionOnNonEnumValue(): void
    {
        $testClass = new class () {};

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('It is only possible to get values of backedEnums, "%s" provided', $testClass::class));

        /** @phpstan-ignore-next-line as not everyone has PHPStan to tell them not to pass something else than an Enum FQN */
        BackedEnum::values($testClass::class);
    }

    /**
     * @covers ::toArray
     *
     * @throws InvalidArgumentException
     */
    public function testToArray(): void
    {
        static::assertSame(
            BackedEnum::toArray(TestEnumBackedByString::class),
            ['FOO' => 'foo', 'FIZ' => 'fiz'],
        );
        static::assertSame(
            BackedEnum::toArray(TestEnumBackedByInt::class),
            ['FOO' => 42, 'FIZ' => 43],
        );
    }

    /** @covers ::toArray */
    public function testToArrayThrowsExceptionOnNonEnumValue(): void
    {
        $testClass = new class () {};

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('It is only possible to get an array of key/value pairs for backedEnums, "%s" provided', $testClass::class));

        /** @phpstan-ignore-next-line as not everyone has PHPStan to tell them not to pass something else than an Enum FQN */
        BackedEnum::toArray($testClass::class);
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
        BackedEnum::fromName(TestEnumBackedByString::class, 'BAR');
    }

    /** @covers ::getCaseAttributes */
    public function testGetCaseAttributes(): void
    {
        self::assertEquals(
            [],
            BackedEnum::getCaseAttributes(TestEnumBackedByInt::FOO)
        );
        self::assertEquals(
            [],
            BackedEnum::getCaseAttributes(TestEnumBackedByInt::FOO)
        );
        self::assertEquals(
            [
                new TestBackedEnumAttributeWithoutConstructorArguments(),
                new TestBackedEnumAttributeWithConstructorArguments('bar')
            ],
            BackedEnum::getCaseAttributes(TestEnumBackedByInt::FIZ)
        );
        self::assertEquals(
            [
                new TestBackedEnumAttributeWithoutConstructorArguments(),
                new TestBackedEnumAttributeWithConstructorArguments('bar')
            ],
            BackedEnum::getCaseAttributes(TestEnumBackedByInt::FIZ)
        );
    }
}

#[Attribute]
class TestBackedEnumAttributeWithoutConstructorArguments
{
}

#[Attribute]
class TestBackedEnumAttributeWithConstructorArguments
{
    public function __construct(public readonly string $foo)
    {
    }
}

enum TestEnumBackedByString: string
{
    case FOO = 'foo';

    #[TestBackedEnumAttributeWithoutConstructorArguments]
    #[TestBackedEnumAttributeWithConstructorArguments('bar')]
    case FIZ = 'fiz';
}

enum TestEnumBackedByInt: int
{
    case FOO = 42;
    case FIZ = 43;
}
