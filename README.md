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

To begin, you need to create an account on [Optimole][1] and get your API key. 

### Initializing the SDK

You can then initialize the SDK with your API key using the `Optimole` facade:

```php
use Optimole\Sdk\Optimole;

Optimole::init('your-api-key', $options);
```

The second argument of the `init` method is optional. It allows you to pass options to the SDK that can be used to configure it. These options are:

 * `base_domain`: The base domain to connect to Optimole's API. Default is `i.optimole.com`.
 * `cache_buster`: A string value that will be appended to the URL of the optimized assets to bust Optimole's cache.
 * `dashboard_api_url`: The URL of the dashboard API. Default is `https://dashboard.optimole.com/api`.
 * `dashboard_api_key`: The API key to use for the dashboard API.
 * `upload_api_credentials`: An array with the credentials to use for the upload API. The array should contain the keys `userKey` and `secret`. The default is empty and the SDK will use the API key provided in the `init` method to fetch them from the dashboard API.
 * `upload_api_url`: The URL of the upload API. Default is `https://generateurls-prod.i.optimole.com/upload`.

### Optimizing Images and Assets

The `Optimole` facade is your starting point for creating optimized images or other assets. You can control the optimization properties using the fluent interface provided by the SDK. Here's an example of how to optimize an image by changing its quality and cropping it:

```php
use Optimole\Sdk\Optimole;

$image = Optimole::image('https://example.com/image.jpg')->quality(80)->resize('crop');
```

You can get the optimized image URL using the `getUrl` method or casting the object to a string:

```php
echo $image->getUrl();
echo (string) $image;
```

### Offloading Images to Optimole

The SDK also provides a way to offload images to Optimole. This is useful when you want to serve images from Optimole's content delivery network. Here's an example of how to offload an image:

```php
use Optimole\Sdk\Optimole;

$imageId = Optimole::offload()->uploadImage('path/to/image.jpg', 'https://url/to/image.jpg');
```

This will upload the image to Optimole and return the image ID. You can then use this image ID to interact with the image. For example, you can get the URL of the offloaded image:

```php
use Optimole\Sdk\Optimole;

$imageUrl = Optimole::offload()->getImageUrl($imageId);
```

## Contributing

Install dependencies using composer and run the test suite:

```console
$ composer install
$ vendor/bin/phpunit
```

[1]: https://optimole.com
