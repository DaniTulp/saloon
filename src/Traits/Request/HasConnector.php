<?php

declare(strict_types=1);

namespace Saloon\Traits\Request;

use GuzzleHttp\Promise\PromiseInterface;
use Saloon\Contracts\Connector;
use Saloon\Contracts\MockClient;
use Saloon\Contracts\Response;
use Saloon\Contracts\Sender;
use Saloon\Http\PendingRequest;

trait HasConnector
{
    /**
     * The loaded connector used in requests.
     *
     * @var \Saloon\Contracts\Connector|null
     */
    private ?Connector $loadedConnector = null;

    /**
     *  Retrieve the loaded connector.
     *
     * @return \Saloon\Contracts\Connector
     */
    public function connector(): Connector
    {
        return $this->loadedConnector ??= $this->resolveConnector();
    }

    /**
     * Set the loaded connector at runtime.
     *
     * @param Connector $connector
     * @return $this
     */
    public function setConnector(Connector $connector): static
    {
        $this->loadedConnector = $connector;

        return $this;
    }

    /**
     * Create a new connector instance.
     *
     * @return Connector
     */
    protected function resolveConnector(): Connector
    {
        return new $this->connector;
    }

    /**
     * Access the HTTP sender
     *
     * @return \Saloon\Contracts\Sender
     */
    public function sender(): Sender
    {
        return $this->connector()->sender();
    }

    /**
     * Create a pending request
     *
     * @param \Saloon\Contracts\MockClient|null $mockClient
     * @return \Saloon\Http\PendingRequest
     */
    public function createPendingRequest(MockClient $mockClient = null): PendingRequest
    {
        return $this->connector()->createPendingRequest($this, $mockClient);
    }

    /**
     * Send a request synchronously
     *
     * @param \Saloon\Contracts\MockClient|null $mockClient
     * @return \Saloon\Contracts\Response
     */
    public function send(MockClient $mockClient = null): Response
    {
        return $this->connector()->send($this, $mockClient);
    }

    /**
     * Send a request asynchronously
     *
     * @param \Saloon\Contracts\MockClient|null $mockClient
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function sendAsync(MockClient $mockClient = null): PromiseInterface
    {
        return $this->connector()->sendAsync($this, $mockClient);
    }
}
