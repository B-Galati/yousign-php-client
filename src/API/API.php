<?php

declare(strict_types=1);

namespace YouSignClient\API;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use YouSignClient\Builder;
use YouSignClient\YouSignClient;

use function json_encode;

abstract class API
{
    protected Builder $client;
    protected YouSignClient $youSignClient;

    protected RequestFactoryInterface $requestFactory;
    protected StreamFactoryInterface $streamFactory;

    public function __construct(YouSignClient $youSignClient)
    {
        $this->youSignClient  = $youSignClient;
        $this->client         = $youSignClient->getHttpClientBuilder();
        $this->requestFactory = $youSignClient->getRequestFactoryInterface();
        $this->streamFactory  = $youSignClient->getStreamFactoryInterface();
    }

    /**
     * @param array|String[] $post
     */
    protected function send(RequestInterface $request, array $post): ResponseInterface
    {
        $body    = $this->client->getStreamFactory()->createStream(json_encode($post));
        $request = $request->withBody($body);

        return $this->client->getHttpClient()->sendRequest($request);
    }
}
