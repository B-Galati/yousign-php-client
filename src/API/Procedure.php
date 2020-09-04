<?php

declare(strict_types=1);

namespace YouSignClient\API;

use Psr\Http\Message\ResponseInterface;

class Procedure extends API
{
    public const API_ENDPOINT = '/procedures';

    /**
     * @param string[] $members
     */
    public function create(string $name, string $description, array $members, bool $start = false): ?ResponseInterface
    {
        $post = [
            'name' => $name,
            'description' => $description,
            'members' => $members,
            'start' => $start,
        ];

        $request = $this->requestFactory->createRequest(
            'POST',
            $this->youSignClient->getApiBaseUrl() . self::API_ENDPOINT
        );

        return $this->send($request, $post);
    }

    public function get(string $procedureId): ResponseInterface
    {
        $request = $this->requestFactory->createRequest(
            'GET',
            $this->youSignClient->getApiBaseUrl() . self::API_ENDPOINT . '/' . $procedureId
        );

        return $this->client->getHttpClient()->sendRequest($request);
    }

    public function addMember(
        string $firstName,
        string $lastName,
        string $email,
        string $phone,
        string $procedureId
    ): ?ResponseInterface {
        $post = [
            'firstname' => $firstName,
            'lastname' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'procedure' => '/procedures' . $procedureId,
        ];

        $request = $this->client->getRequestFactory()->createRequest(
            'POST',
            $this->youSignClient->getApiBaseUrl() . self::API_ENDPOINT
        );

        return $this->send($request, $post);
    }

    public function start(string $procedureId): ResponseInterface
    {
        $post = ['start' => true];

        $request = $this->client->getRequestFactory()->createRequest(
            'PUT',
            $this->youSignClient->getApiBaseUrl() . self::API_ENDPOINT . '/' . $procedureId
        );

        return $this->send($request, $post);
    }

    public function delete(string $procedureId): ResponseInterface
    {
        $request = $this->client->getRequestFactory()->createRequest(
            'DELETE',
            $this->youSignClient->getApiBaseUrl() . self::API_ENDPOINT . '/' . $procedureId
        );

        return $this->client->getHttpClient()->sendRequest($request);
    }
}
