<?php

declare(strict_types=1);

namespace YouSignClient\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;

final class Headers implements Plugin
{
    /** @var $headers string[] */
    private array $headers;

    public function __construct()
    {
        $this->headers                 = [];
        $this->headers['Content-Type'] = 'application/json';
    }

    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        foreach ($this->headers as $header => $value) {
            $request = $request->withHeader($header, $value);
        }

        return $next($request);
    }
}
