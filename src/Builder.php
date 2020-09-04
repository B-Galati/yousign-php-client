<?php

declare(strict_types=1);

namespace YouSignClient;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin;
use Http\Client\Common\PluginClientFactory;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

class Builder
{
    /**
     * The object that sends HTTP messages.
     */
    private ClientInterface $httpClient;

    /**
     * The HTTP request factory.
     */
    private RequestFactoryInterface $requestFactory;

    /**
     * The HTTP stream factory.
     */
    private StreamFactoryInterface $streamFactory;

    /**
     * The URI factory.
     */
    private UriFactoryInterface $uriFactory;

    /**
     * The currently registered plugins.
     *
     * @var Plugin[]
     */
    private array $plugins = [];

    /**
     * A HTTP client with all our plugins.
     */
    private ?HttpMethodsClientInterface $pluginClient = null;

    /**
     * Create a new http client builder instance.
     *
     * @return void
     */
    public function __construct(
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?StreamFactoryInterface $streamFactory = null,
        ?UriFactoryInterface $uriFactory = null
    ) {
        $this->httpClient     = $httpClient ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory  = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
        $this->uriFactory     = $uriFactory ?? Psr17FactoryDiscovery::findUrlFactory();
    }

    public function getHttpClient(): HttpMethodsClientInterface
    {
        if ($this->pluginClient === null) {
            $plugins = $this->plugins;

            $this->pluginClient = new HttpMethodsClient(
                (new PluginClientFactory())->createClient($this->httpClient, $plugins),
                $this->requestFactory,
                $this->streamFactory
            );
        }

        return $this->pluginClient;
    }

    /**
     * Get the request factory.
     */
    public function getRequestFactory(): RequestFactoryInterface
    {
        return $this->requestFactory;
    }

    /**
     * Get the stream factory.
     */
    public function getStreamFactory(): StreamFactoryInterface
    {
        return $this->streamFactory;
    }

    /**
     * Get the URI factory.
     */
    public function getUriFactory(): UriFactoryInterface
    {
        return $this->uriFactory;
    }

    /**
     * Add a new plugin to the end of the plugin chain.
     */
    public function addPlugin(Plugin $plugin): void
    {
        $this->plugins[]    = $plugin;
        $this->pluginClient = null;
    }

    /**
     * Remove a plugin by its fully qualified class name (FQCN).
     */
    public function removePlugin(string $fqcn): void
    {
        foreach ($this->plugins as $idx => $plugin) {
            if (! ($plugin instanceof $fqcn)) {
                continue;
            }

            unset($this->plugins[$idx]);
            $this->pluginClient = null;
        }
    }
}
