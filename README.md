# Optimole PHP SDK

[![Actions Status](https://github.com/Codeinwp/optimole-php-sdk/workflows/Continuous%20Integration/badge.svg)](https://github.com/Codeinwp/optimole-php-sdk/actions)

The Optimole PHP SDK makes it easy for PHP developers to integrate [Optimole][1] cloud-based image optimization service in their PHP project.

## Requirements

 * PHP >= 7.4

## Installation

Install the Optimole PHP SDK in your project using composer:

```
$ composer require codeinwp/optimole-sdk
```

## Usage

To begin, you need to create an account on [Optimole][1] and get your API key. You can then initialize the SDK with your 
API key using the `Optimole` facade:

```php
use Codeinwp\Optimole\Optimole;

Optimole::init('your-api-key');
```

The `Optimole` facade is your starting point for creating optimized images or other assets. You can control the optimization 
properties using the fluent interface provided by the SDK. Here's an example of how to optimize an image by changing its
quality and cropping it:

```php
use Codeinwp\Optimole\Optimole;

$image = Optimole::image('https://example.com/image.jpg')->quality(80)->resize('crop');
```

You can get the optimized image URL using the `getUrl` method or casting the object to a string:

```php
echo $image->getUrl();
echo (string) $image;
```

## Contributing

Install dependencies using composer and run the test suite:

```console
$ composer install
$ vendor/bin/phpunit
```

[1]: https://optimole.com
