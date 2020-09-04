<?php

declare(strict_types=1);

namespace YouSignClient\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;

use function sprintf;

final class Authentication implements Plugin
{
    /** @var $headers string[] */
    private array $headers;

    public function __construct(string $apiKey)
    {
        $this->headers                  = [];
        $this->headers['Authorization'] = sprintf('Bearer %s', $apiKey);
    }

    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        foreach ($this->headers as $header => $value) {
            $request = $request->withHeader($header, $value);
        }

        return $next($request);
    }
}
