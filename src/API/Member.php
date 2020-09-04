<?php

declare(strict_types=1);

namespace YouSignClient\API;

use Psr\Http\Message\ResponseInterface;

class Member extends API
{
    public const API_ENDPOINT = '/members';

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
            'procedure' => '/procedures/' . $procedureId,
        ];

        $request = $this->client->getRequestFactory()->createRequest(
            'POST',
            $this->youSignClient->getApiBaseUrl() . self::API_ENDPOINT
        );

        return $this->send($request, $post);
    }
}
