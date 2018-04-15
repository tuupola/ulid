# [WIP] PHP ULID

This library implements the [Universally Unique Lexicographically Sortable Identifier](https://github.com/alizain/ulid) from Alizain Feerasta.

[![Latest Version](https://img.shields.io/packagist/v/tuupola/ulid.svg?style=flat-square)](https://packagist.org/packages/tuupola/ulid)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/tuupola/ulid/master.svg?style=flat-square)](https://travis-ci.org/tuupola/ulid)
[![Coverage](http://img.shields.io/codecov/c/github/tuupola/ulid.svg?style=flat-square)](https://codecov.io/github/tuupola/ulid)

## Install

Install with [composer](https://getcomposer.org/).

``` bash
$ composer require tuupola/ulid
```

## Usage

``` php
use Tuupola\Ulid;

$ulid = new Ulid;

print $ulid; /* 0001DD70YKYBES1P98DHWKTWZW */

//$ulid = ulid::fromString("0o5Fs0EELR0fUjHjbCnEtdUwQe3");

print $ulid->timestamp(); /* 1523811283 */
print bin2hex($ulid->payload()); /* f2dd90d9286c793d73fc */

$datetime = (new \DateTimeImmutable)
    ->setTimestamp($ulid->unixtime())
    ->setTimeZone(new \DateTimeZone("UTC"));

print $datetime->format("Y-m-d H:i:s"); /* 2018-04-15 16:54:43 */
```

## Testing

You can run tests either manually or automatically on every code change. Automatic tests require [entr](http://entrproject.org/) to work.

``` bash
$ composer test
```

``` bash
$ brew install entr
$ composer watch
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email tuupola@appelsiini.net instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.