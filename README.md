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

    #[HasSpecialMeaning]
    case Bar;
}
```

If we want to get a value by it's name from a string, we can call `fromName` or `tryFromName`;
```php
UnitEnum::fromName(Example::class, 'Foo'); // Example::Foo
UnitEnum::tryFromName(Example::class, 'Foo'); // Example::Foo
```

The difference between the two methods is the way non-existing names are handled;
```php

UnitEnum::fromName(Example::class, 'Example'); // @throws NameNotFoundException
UnitEnum::tryFromName(Example::class, 'Example'); // null
```

Another method that this package provides is to get an array of all the names for an enum;
```php
UnitEnum::names(Example::class); // ['Foo', 'Bar']
```

And finally some methods to easily get the attributes of an enum case:
```php
UnitEnum::hasCaseAttributes(Example::Bar, HasSpecialMeaning); // true
UnitEnum::getCaseAttributes(Example::Bar); // [new HasSpecialMeaning()]
```

### BackedEnum

Let's assume we have the following BackedEnum: (It doesn't matter if an enum is backed by a string or an integer)
```php
enum Example: string {
    case Foo = 'Foo';

    #[HasSpecialMeaning]
    case Bar = 'Bar';
}
```

If we want to get a value by it's name from a string, we can call `fromName` or `tryFromName`;
```php
BackedEnum::fromName(Example::class, 'Foo'); // Example::Foo
BackedEnum::tryFromName(Example::class, 'Foo'); // Example::Foo
```

The difference between the two methods is the way non-existing names are handled;
```php

BackedEnum::fromName(Example::class, 'Example'); // @throws NameNotFoundException
BackedEnum::tryFromName(Example::class, 'Example'); // null
```

Another method that this package provides is to get an array of all the names for an enum;
```php
BackedEnum::names(Example::class); // ['Foo', 'Bar']
```

And finally some methods to easily get the attributes of an enum case:
```php
BackedEnum::hasCaseAttributes(Example::Bar, HasSpecialMeaning); // true
BackedEnum::getCaseAttributes(Example::Bar); // [new HasSpecialMeaning()]
```
