<?php

declare(strict_types=1);

/*
 * This file is part of Optimole PHP SDK.
 *
 * (c) Optimole Team <friends@optimole.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Optimole\Sdk\Tests\Unit\Offload;

use Optimole\Sdk\Exception\BadResponseException;
use Optimole\Sdk\Exception\InvalidArgumentException;
use Optimole\Sdk\Exception\InvalidDashboardApiResponseException;
use Optimole\Sdk\Exception\InvalidUploadApiResponseException;
use Optimole\Sdk\Http\ClientInterface;
use Optimole\Sdk\Offload\Manager;
use Optimole\Sdk\Tests\Mock\FunctionMockTrait;
use PHPUnit\Framework\TestCase;

class ManagerTest extends TestCase
{
    use FunctionMockTrait;

    public function testConstructorWithMissingDashboardApiOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing "dashboard_api_url" option');

        new Manager($this->createMock(ClientInterface::class), 'optimole_key', ['upload_api_url' => 'https://upload_api_url']);
    }

    public function testConstructorWithMissingUploadApiOption()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing "upload_api_url" option');

        new Manager($this->createMock(ClientInterface::class), 'optimole_key', ['dashboard_api_url' => 'https://dashboard_api_url']);
    }

    public function testDeleteImage()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(2))
                   ->method('sendRequest')
                   ->willReturnCallback(function (...$args) {
                       static $expected = [
                           [
                               ['post', 'https://dashboard_api_url/optml/v2/account/details', null, ['Authorization' => 'Bearer optimole_key', 'Content-Type' => 'application/json']],
                               ['data' => ['cdn_key' => 'cdn_key', 'cdn_secret' => 'cdn_secret']],
                           ],
                           [
                               ['post', 'https://upload_api_url', ['userKey' => 'cdn_key', 'secret' => 'cdn_secret', 'id' => 'image_id', 'deleteUrl' => 'true'], ['Content-Type' => 'application/json']],
                               ['success'],
                           ],
                       ];

                       list($expectedArgument, $return) = array_shift($expected);

                       $this->assertSame($expectedArgument, $args);

                       return $return;
                   });

        (new Manager($httpClient, 'optimole_key', ['dashboard_api_url' => 'https://dashboard_api_url', 'upload_api_url' => 'https://upload_api_url']))->deleteImage('image_id');
    }

    public function testDeleteImageWithBadResponseException()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(2))
                   ->method('sendRequest')
                   ->willReturnCallback(function (...$args) {
                       static $expected = [
                           [
                               ['post', 'https://dashboard_api_url/optml/v2/account/details', null, ['Authorization' => 'Bearer optimole_key', 'Content-Type' => 'application/json']],
                               ['data' => ['cdn_key' => 'cdn_key', 'cdn_secret' => 'cdn_secret']],
                           ],
                           [
                               ['post', 'https://upload_api_url', ['userKey' => 'cdn_key', 'secret' => 'cdn_secret', 'id' => 'image_id', 'deleteUrl' => 'true'], ['Content-Type' => 'application/json']],
                               BadResponseException::class,
                           ],
                       ];

                       list($expectedArgument, $return) = array_shift($expected);

                       $this->assertSame($expectedArgument, $args);

                       if (is_a($return, \Throwable::class, true)) {
                           throw new $return();
                       }

                       return $return;
                   });

        (new Manager($httpClient, 'optimole_key', ['dashboard_api_url' => 'https://dashboard_api_url', 'upload_api_url' => 'https://upload_api_url']))->deleteImage('image_id');
    }

    public function testDeleteImageWithNonBadResponseException()
    {
        $this->expectException(\RuntimeException::class);

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(2))
                   ->method('sendRequest')
                   ->willReturnCallback(function (...$args) {
                       static $expected = [
                           [
                               ['post', 'https://dashboard_api_url/optml/v2/account/details', null, ['Authorization' => 'Bearer optimole_key', 'Content-Type' => 'application/json']],
                               ['data' => ['cdn_key' => 'cdn_key', 'cdn_secret' => 'cdn_secret']],
                           ],
                           [
                               ['post', 'https://upload_api_url', ['userKey' => 'cdn_key', 'secret' => 'cdn_secret', 'id' => 'image_id', 'deleteUrl' => 'true'], ['Content-Type' => 'application/json']],
                               \RuntimeException::class,
                           ],
                       ];

                       list($expectedArgument, $return) = array_shift($expected);

                       $this->assertSame($expectedArgument, $args);

                       if (is_a($return, \Throwable::class, true)) {
                           throw new $return();
                       }

                       return $return;
                   });

        (new Manager($httpClient, 'optimole_key', ['dashboard_api_url' => 'https://dashboard_api_url', 'upload_api_url' => 'https://upload_api_url']))->deleteImage('image_id');
    }

    public function testGetImageUrl()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(2))
                   ->method('sendRequest')
                   ->willReturnCallback(function (...$args) {
                       static $expected = [
                           [
                               ['post', 'https://dashboard_api_url/optml/v2/account/details', null, ['Authorization' => 'Bearer optimole_key', 'Content-Type' => 'application/json']],
                               ['data' => ['cdn_key' => 'cdn_key', 'cdn_secret' => 'cdn_secret']],
                           ],
                           [
                               ['post', 'https://upload_api_url', ['userKey' => 'cdn_key', 'secret' => 'cdn_secret', 'id' => 'image_id', 'getUrl' => 'true'], ['Content-Type' => 'application/json']],
                               ['getUrl' => 'https://cdn.optimole.com/image_id'],
                           ],
                       ];

                       list($expectedArgument, $return) = array_shift($expected);

                       $this->assertSame($expectedArgument, $args);

                       return $return;
                   });

        $this->assertSame('https://cdn.optimole.com/image_id', (new Manager($httpClient, 'optimole_key', ['dashboard_api_url' => 'https://dashboard_api_url', 'upload_api_url' => 'https://upload_api_url']))->getImageUrl('image_id'));
    }

    public function testGetImageUrlReturnNullWithBadResponseException()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(2))
                   ->method('sendRequest')
                   ->willReturnCallback(function (...$args) {
                       static $expected = [
                           [
                               ['post', 'https://dashboard_api_url/optml/v2/account/details', null, ['Authorization' => 'Bearer optimole_key', 'Content-Type' => 'application/json']],
                               ['data' => ['cdn_key' => 'cdn_key', 'cdn_secret' => 'cdn_secret']],
                           ],
                           [
                               ['post', 'https://upload_api_url', ['userKey' => 'cdn_key', 'secret' => 'cdn_secret', 'id' => 'image_id', 'getUrl' => 'true'], ['Content-Type' => 'application/json']],
                               BadResponseException::class,
                           ],
                       ];

                       list($expectedArgument, $return) = array_shift($expected);

                       $this->assertSame($expectedArgument, $args);

                       if (is_a($return, \Throwable::class, true)) {
                           throw new $return();
                       }

                       return $return;
                   });

        $this->assertNull((new Manager($httpClient, 'optimole_key', ['dashboard_api_url' => 'https://dashboard_api_url', 'upload_api_url' => 'https://upload_api_url']))->getImageUrl('image_id'));
    }

    public function testGetImageUrlWithMissingGetUrlKey()
    {
        $this->expectException(InvalidUploadApiResponseException::class);
        $this->expectExceptionMessage('Unable to get image URL from upload API');

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(2))
                   ->method('sendRequest')
                   ->willReturnCallback(function (...$args) {
                       static $expected = [
                           [
                               ['post', 'https://dashboard_api_url/optml/v2/account/details', null, ['Authorization' => 'Bearer optimole_key', 'Content-Type' => 'application/json']],
                               ['data' => ['cdn_key' => 'cdn_key', 'cdn_secret' => 'cdn_secret']],
                           ],
                           [
                               ['post', 'https://upload_api_url', ['userKey' => 'cdn_key', 'secret' => 'cdn_secret', 'id' => 'image_id', 'getUrl' => 'true'], ['Content-Type' => 'application/json']],
                               [],
                           ],
                       ];

                       list($expectedArgument, $return) = array_shift($expected);

                       $this->assertSame($expectedArgument, $args);

                       return $return;
                   });

        (new Manager($httpClient, 'optimole_key', ['dashboard_api_url' => 'https://dashboard_api_url', 'upload_api_url' => 'https://upload_api_url']))->getImageUrl('image_id');
    }

    public function testGetUsage()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
                   ->method('sendRequest')
                   ->with($this->identicalTo('post'), $this->identicalTo('https://dashboard_api_url/optml/v2/account/details'), $this->identicalTo(null), $this->identicalTo(['Authorization' => 'Bearer optimole_key', 'Content-Type' => 'application/json']))
                   ->willReturn(['data' => ['offload_limit' => 5000, 'offloaded_images' => 42]]);

        $usage = (new Manager($httpClient, 'optimole_key', ['dashboard_api_url' => 'https://dashboard_api_url', 'upload_api_url' => 'https://upload_api_url']))->getUsage();

        $this->assertSame(42, $usage->getCurrent());
        $this->assertSame(5000, $usage->getLimit());
    }

    public function testGetUsageWithMissingOffloadedImages()
    {
        $this->expectException(InvalidDashboardApiResponseException::class);
        $this->expectExceptionMessage('Dashboard API did not return details about the offload service usage');

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
                   ->method('sendRequest')
                   ->with($this->identicalTo('post'), $this->identicalTo('https://dashboard_api_url/optml/v2/account/details'), $this->identicalTo(null), $this->identicalTo(['Authorization' => 'Bearer optimole_key', 'Content-Type' => 'application/json']))
                   ->willReturn(['data' => ['offload_limit' => 5000]]);

        (new Manager($httpClient, 'optimole_key', ['dashboard_api_url' => 'https://dashboard_api_url', 'upload_api_url' => 'https://upload_api_url']))->getUsage();
    }

    public function testGetUsageWithMissingOffloadLimit()
    {
        $this->expectException(InvalidDashboardApiResponseException::class);
        $this->expectExceptionMessage('Dashboard API did not return details about the offload service usage');

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->once())
                   ->method('sendRequest')
                   ->with($this->identicalTo('post'), $this->identicalTo('https://dashboard_api_url/optml/v2/account/details'), $this->identicalTo(null), $this->identicalTo(['Authorization' => 'Bearer optimole_key', 'Content-Type' => 'application/json']))
                   ->willReturn(['data' => ['offloaded_images' => 42]]);

        (new Manager($httpClient, 'optimole_key', ['dashboard_api_url' => 'https://dashboard_api_url', 'upload_api_url' => 'https://upload_api_url']))->getUsage();
    }

    public function testUpdateImageMetadata()
    {
        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient->expects($this->exactly(2))
                   ->method('sendRequest')
                   ->willReturnCallback(function (...$args) {
                       static $expected = [
                           [
                               ['post', 'https://dashboard_api_url/optml/v2/account/details', null, ['Authorization' => 'Bearer optimole_key', 'Content-Type' => 'application/json']],
                               ['data' => ['cdn_key' => 'cdn_key', 'cdn_secret' => 'cdn_secret']],
                           ],
                           [
                               ['post', 'https://upload_api_url', ['userKey' => 'cdn_key', 'secret' => 'cdn_secret', 'id' => 'image_id', 'originalFileSize' => 42, 'height' => 100, 'width' => 200, 'updateDynamo' => 'success'], ['Content-Type' => 'application/json']],
                               ['success'],
                           ],
                       ];

                       list($expectedArgument, $return) = array_shift($expected);

                       $this->assertSame($expectedArgument, $args);

                       return $return;
                   });

        (new Manager($httpClient, 'optimole_key', ['dashboard_api_url' => 'https://dashboard_api_url', 'upload_api_url' => 'https://upload_api_url']))->updateImageMetadata('image_id', 42, 100, 200);
    }

    public function testUpdateImageMetadataWithInvalidImageHeight()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Image height must be "auto" or an integer.');

        (new Manager($this->createMock(ClientInterface::class), 'optimole_key', ['dashboard_api_url' => 'https://dashboard_api_url', 'upload_api_url' => 'https://upload_api_url']))->updateImageMetadata('image_id', 42, '', 200);
    }

    public function testUpdateImageMetadataWithInvalidImageWidth()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Image width must be "auto" or an integer.');

        (new Manager($this->createMock(ClientInterface::class), 'optimole_key', ['dashboard_api_url' => 'https://dashboard_api_url', 'upload_api_url' => 'https://upload_api_url']))->updateImageMetadata('image_id', 42, 100, '');
    }

    public function testUploadImageWhenFileDoesntExist()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('File "non_existent_file" does not exist');

        $httpClient = $this->createMock(ClientInterface::class);

        (new Manager($httpClient, 'optimole_key', ['dashboard_api_url' => 'https://dashboard_api_url', 'upload_api_url' => 'https://upload_api_url']))->uploadImage('non_existent_file', 'image_url');
    }

    /**
     * @runInSeparateProcess
     */
    public function testUploadImageWhenFileIsntReadable()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('File "non_readable_file" is not readable');

        $file_exists = $this->getFunctionMock($this->getNamespace(Manager::class), 'file_exists');
        $httpClient = $this->createMock(ClientInterface::class);

        $file_exists->expects($this->once())
                    ->with($this->identicalTo('non_readable_file'))
                    ->willReturn(true);

        (new Manager($httpClient, 'optimole_key', ['dashboard_api_url' => 'https://dashboard_api_url', 'upload_api_url' => 'https://upload_api_url']))->uploadImage('non_readable_file', 'image_url');
    }
}
