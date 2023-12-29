<picture>
    <source srcset="https://github.com/PrinsFrank/enums/raw/main/docs/images/banner_dark.png" media="(prefers-color-scheme: dark)">
    <img src="https://github.com/PrinsFrank/enums/raw/main/docs/images/banner_light.png" alt="Banner">
</picture>

# Enums

![GitHub](https://img.shields.io/github/license/prinsfrank/enums)
![PHP Version Support](https://img.shields.io/packagist/php-v/prinsfrank/enums)
[![codecov](https://codecov.io/gh/PrinsFrank/enums/branch/main/graph/badge.svg?token=9O3VB563MU)](https://codecov.io/gh/PrinsFrank/enums)
![PHPStan Level](https://img.shields.io/badge/PHPStan-level%209-brightgreen.svg?style=flat)

**Adds missing strictly typed methods to work with enums**

## Setup

> **Note**
> Make sure you are running PHP 8.1 or higher to use this package

To start right away, run the following command in your composer project;

```composer require prinsfrank/enums```

Or for development only;

```composer require prinsfrank/enums --dev```

## How this package works

### UnitEnum

Let's assume we have the following UnitEnum:
```php
enum Example {
    case Foo;
    case Bar;
}
```

If we want to get a value by it's name from a string, we can call `fromName` or `tryFromName`;
```php
\PrinsFrank\Enums\UnitEnum::fromName(Example::class, 'Foo'); // Example::Foo
\PrinsFrank\Enums\UnitEnum::tryFromName(Example::class, 'Foo'); // Example::Foo
```

The difference between the two methods is the way non-existing names are handled;
```php

\PrinsFrank\Enums\UnitEnum::fromName(Example::class, 'Example'); // @throws NameNotFoundException
\PrinsFrank\Enums\UnitEnum::tryFromName(Example::class, 'Example'); // null
```

The last method that this package provides is to get an array of all the names for an enum;
```php
\PrinsFrank\Enums\UnitEnum::names(Example::class); // ['Foo', 'Bar']
```

### BackedEnum

Let's assume we have the following BackedEnum: (It doesn't matter if an enum is backed by a string or an integer)
```php
enum Example: string {
    case Foo = 'Foo';
    case Bar = 'Bar';
}
```

If we want to get a value by it's name from a string, we can call `fromName` or `tryFromName`;
```php
\PrinsFrank\Enums\BackedEnum::fromName(Example::class, 'Foo'); // Example::Foo
\PrinsFrank\Enums\BackedEnum::tryFromName(Example::class, 'Foo'); // Example::Foo
```

The difference between the two methods is the way non-existing names are handled;
```php

\PrinsFrank\Enums\BackedEnum::fromName(Example::class, 'Example'); // @throws NameNotFoundException
\PrinsFrank\Enums\BackedEnum::tryFromName(Example::class, 'Example'); // null
```

The last method that this package provides is to get an array of all the names for an enum;
```php
\PrinsFrank\Enums\BackedEnum::names(Example::class); // ['Foo', 'Bar']
```
