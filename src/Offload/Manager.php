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

namespace Optimole\Sdk\Offload;

use Optimole\Sdk\Exception\BadResponseException;
use Optimole\Sdk\Exception\InvalidArgumentException;
use Optimole\Sdk\Exception\InvalidDashboardApiResponseException;
use Optimole\Sdk\Exception\InvalidUploadApiResponseException;
use Optimole\Sdk\Exception\RuntimeException;
use Optimole\Sdk\Exception\UploadApiException;
use Optimole\Sdk\Exception\UploadFailedException;
use Optimole\Sdk\Exception\UploadLimitException;
use Optimole\Sdk\Http\ClientInterface;
use Optimole\Sdk\ValueObject\OffloadUsage;

class Manager
{
    /**
     * The HTTP client.
     */
    private ClientInterface $httpClient;

    /**
     * The manager options.
     */
    private array $options;

    /**
     * Constructor.
     */
    public function __construct(ClientInterface $httpClient, array $options = [])
    {
        if (empty($options['dashboard_api_url'])) {
            throw new InvalidArgumentException('Missing "dashboard_api_url" option');
        } elseif (empty($options['upload_api_url'])) {
            throw new InvalidArgumentException('Missing "upload_api_url" option');
        }

        $this->httpClient = $httpClient;
        $this->options = array_merge([
            'upload_api_credentials' => [],
        ], $options);

        $this->options['dashboard_api_url'] = rtrim($this->options['dashboard_api_url'], '/');
        $this->options['upload_api_url'] = rtrim($this->options['upload_api_url'], '/');
    }

    /**
     * Delete the image with the given image ID.
     */
    public function deleteImage(string $imageId): void
    {
        try {
            $this->requestToUploadApi([
                'id' => $imageId,
                'deleteUrl' => 'true',
            ]);
        } catch (BadResponseException $exception) {
        }
    }

    /**
     * Get the image URL for the given image ID.
     */
    public function getImageUrl(string $imageId): ?string
    {
        try {
            $response = $this->requestToUploadApi([
                'id' => $imageId,
                'getUrl' => 'true',
            ]);
        } catch (BadResponseException $exception) {
            return null;
        }

        if (empty($response['getUrl'])) {
            throw new InvalidUploadApiResponseException('Unable to get image URL from upload API');
        }

        return (string) $response['getUrl'];
    }

    /**
     * Get the offload service usage.
     */
    public function getUsage(): OffloadUsage
    {
        $response = $this->requestToDashboardApi();

        if (!isset($response['data']['offload_limit'], $response['data']['offloaded_images'])) {
            throw new InvalidDashboardApiResponseException('Dashboard API did not return details about the offload service usage');
        }

        return new OffloadUsage((int) $response['data']['offloaded_images'], (int) $response['data']['offload_limit']);
    }

    /**
     * Update the metadata of the image with the given ID.
     */
    public function updateImageMetadata(string $imageId, int $fileSize = 0, $height = 'auto', $width = 'auto', $originalUrl = ''): void
    {
        if ('auto' !== $height && !is_int($height)) {
            throw new InvalidArgumentException('Image height must be "auto" or an integer.');
        } elseif ('auto' !== $width && !is_int($width)) {
            throw new InvalidArgumentException('Image width must be "auto" or an integer.');
        }

        $this->requestToUploadApi([
            'id' => $imageId,
            'originalFileSize' => $fileSize,
            'height' => is_int($height) ? max(0, $height) : $height,
            'width' => is_int($width) ? max(0, $width) : $width,
            'originalUrl' => $originalUrl,
            'updateDynamo' => 'success',
        ]);
    }

    /**
     * Upload an image to Optimole and return its image ID.
     */
    public function uploadImage(string $filename, string $imageUrl): string
    {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException(sprintf('File "%s" does not exist', $filename));
        } elseif (!is_readable($filename)) {
            throw new InvalidArgumentException(sprintf('File "%s" is not readable', $filename));
        }

        $fileMimeType = $this->getMimeType($filename);

        try {
            $response = $this->requestToUploadApi([
                'originalUrl' => $imageUrl,
            ]);
        } catch (BadResponseException $exception) {
            throw new UploadApiException('Unable to get signed URL from upload API', 0, $exception);
        }

        if (isset($response['error']) && 'limit_exceeded' === $response['error']) {
            throw new UploadLimitException(new OffloadUsage((int) $response['count'], (int) $response['limit']));
        } elseif (isset($response['error'])) {
            throw new UploadApiException(sprintf('Upload API returned an error: %s', $response['error']));
        } elseif (isset($response['count'], $response['limit']) && $response['count'] >= $response['limit']) {
            throw new UploadLimitException(new OffloadUsage((int) $response['count'], (int) $response['limit']));
        } elseif (!isset($response['tableId'], $response['uploadUrl'])) {
            throw new InvalidUploadApiResponseException('Upload API did not return the table ID and upload URL');
        }

        $imageId = (string) $response['tableId'];
        $uploadUrl = (string) $response['uploadUrl']; 

        $image = file_get_contents($filename);

        if (false === $image) {
            throw new RuntimeException(sprintf('Unable to get file "%s" content', $filename));
        }

        try {
            $this->httpClient->sendRequest('PUT', $uploadUrl, $image, [
                'Content-Type' => $fileMimeType,
            ]);
        } catch (BadResponseException $exception) {
            throw new UploadFailedException(sprintf('Unable to upload file "%s": %s', $filename, $exception->getMessage()), 0, $exception);
        }

        $imagesize = getimagesize($filename);

        $this->updateImageMetadata($imageId, filesize($filename) ?: 0, $imagesize && !empty($imagesize[1]) ? $imagesize[1] : 'auto', $imagesize && !empty($imagesize[0]) ? $imagesize[0] : 'auto', $imageUrl);

        return $imageId;
    }

    /**
     * Get the MIME type of the given file.
     */
    private function getMimeType(string $filename): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        if (false === $finfo) {
            throw new RuntimeException('Unable to open fileinfo database');
        }

        $mimeType = finfo_file($finfo, $filename);

        finfo_close($finfo);

        if (false === $mimeType) {
            throw new RuntimeException(sprintf('Unable to get MIME type for file "%s"', $filename));
        }

        return $mimeType;
    }

    /**
     * Get the upload API credentials from the dashboard API.
     */
    private function getUploadApiCredentialsFromDashboardApi(): array
    {
        $response = $this->requestToDashboardApi();

        if (!isset($response['data']['cdn_key'], $response['data']['cdn_secret'])) {
            throw new InvalidDashboardApiResponseException('Dashboard API did not return upload API credentials');
        }

        return [
            'userKey' => $response['data']['cdn_key'],
            'secret' => $response['data']['cdn_secret'],
        ];
    }

    /**
     * Make a request to the dashboard API.
     */
    private function requestToDashboardApi(): array
    {
        return $this->httpClient->sendRequest('POST', sprintf('%s/optml/v2/account/details', $this->options['dashboard_api_url']), null, [
            'Authorization' => sprintf('Bearer %s', $this->options['dashboard_api_key']),
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Make a request to the upload API.
     */
    private function requestToUploadApi(array $body): array
    {
        if (!isset($this->options['upload_api_credentials']['userKey'], $this->options['upload_api_credentials']['secret'])) {
            $this->options['upload_api_credentials'] = $this->getUploadApiCredentialsFromDashboardApi();
        }

        return $this->httpClient->sendRequest('POST', $this->options['upload_api_url'], array_merge($this->options['upload_api_credentials'], $body), [
            'Content-Type' => 'application/json',
        ]);
    }
}
