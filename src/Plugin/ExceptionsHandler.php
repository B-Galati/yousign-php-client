<?php

declare(strict_types=1);

namespace YouSignClient\Plugin;

use Exception;
use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

final class ExceptionsHandler implements Plugin
{
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        return $next($request)->then(static function (ResponseInterface $response) {
            $status = $response->getStatusCode();

            if ($status >= 400 && $status < 600) {
                throw self::createException($status, $response->getReasonPhrase());
            }

            return $response;
        });
    }

    private static function createException(int $status, string $message): Throwable
    {
        if ($status === 400 || $status === 422) {
            return new Exception($message, $status);
        }

        if ($status === 429) {
            return new Exception($message, $status);
        }

        return new Exception($message, $status);
    }
}
