<?php

declare(strict_types=1);

namespace YouSignClient\API;

use Psr\Http\Message\ResponseInterface;

class File extends API
{
    public const API_ENDPOINT = '/files';

    public function create(string $base64File, string $name): ?ResponseInterface
    {
        $post = [
            'name' => $name,
            'content' => $base64File,
        ];

        $request = $this->requestFactory->createRequest(
            'POST',
            $this->youSignClient->getApiBaseUrl() . '/files'
        );

        return $this->send($request, $post);
    }

    public function get(string $fileId): ResponseInterface
    {
        $request = $this->requestFactory->createRequest(
            'GET',
            $this->youSignClient->getApiBaseUrl() . self::API_ENDPOINT . '/' . $fileId
        );

        return $this->client->getHttpClient()->sendRequest($request);
    }

    public function download(string $fileId): ResponseInterface
    {
        $request = $this->requestFactory->createRequest(
            'GET',
            $this->youSignClient->getApiBaseUrl() . self::API_ENDPOINT . '/' . $fileId . '/download'
        );

        return $this->client->getHttpClient()->sendRequest($request);
    }

    public function update(string $fileId, string $base64File): ?ResponseInterface
    {
        $post = ['content' => $base64File];

        $request = $this->requestFactory->createRequest(
            'PUT',
            $this->youSignClient->getApiBaseUrl() . self::API_ENDPOINT . '/' . $fileId
        );

        return $this->send($request, $post);
    }

    public function delete(string $fileId): ResponseInterface
    {
        $request = $this->client->getRequestFactory()->createRequest(
            'DELETE',
            $this->youSignClient->getApiBaseUrl() . self::API_ENDPOINT . '/' . $fileId
        );

        return $this->client->getHttpClient()->sendRequest($request);
    }
}
