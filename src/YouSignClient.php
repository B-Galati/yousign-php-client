<?php

declare(strict_types=1);

namespace YouSignClient;

use Http\Client\Common\HttpMethodsClientInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use YouSignClient\API\File;
use YouSignClient\API\Member;
use YouSignClient\API\Procedure;
use YouSignClient\Plugin\Authentication;
use YouSignClient\Plugin\Headers;

class YouSignClient
{
    private Builder $httpClientBuilder;

    public const URL_PRODUCTION = 'https://api.yousign.com';
    public const URL_STAGING    = 'https://staging-api.yousign.com';

    private string $apiBaseUrl;

    public function __construct(
        string $mode,
        string $apiKey,
        ?ClientInterface $httpClient = null
    ) {
        $this->httpClientBuilder = $httpClient ? new Builder($httpClient) : new Builder();

        $this->httpClientBuilder->addPlugin(new Headers());
        $this->httpClientBuilder->addPlugin(new Authentication($apiKey));

        if ($mode === 'prod') {
            $this->apiBaseUrl = self::URL_PRODUCTION;
        } else {
            $this->apiBaseUrl = self::URL_STAGING;
        }
    }

    /**
     * Get procedure API.
     */
    public function procedure(): Procedure
    {
        return new Procedure($this);
    }

    /**
     * Get member API.
     */
    public function member(): Member
    {
        return new Member($this);
    }

    /**
     * Get file API.
     */
    public function file(): File
    {
        return new File($this);
    }

    /**
     * Get API base url
     */
    public function getApiBaseUrl(): string
    {
        return $this->apiBaseUrl;
    }

    /**
     * Get the HTTP client builder.
     */
    public function getHttpClientBuilder(): Builder
    {
        return $this->httpClientBuilder;
    }

    /**
     * Get the HTTP client
     */
    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    /**
     * Get the Request Factory Interface
     */
    public function getRequestFactoryInterface(): RequestFactoryInterface
    {
        return $this->getHttpClientBuilder()->getRequestFactory();
    }

    /**
     * Get the Stream Factory Interface
     */
    public function getStreamFactoryInterface(): StreamFactoryInterface
    {
        return $this->getHttpClientBuilder()->getStreamFactory();
    }
}
