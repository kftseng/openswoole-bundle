<?php

declare(strict_types=1);

namespace K911\Swoole\Server\Config;

use Assert\Assertion;
use Generator;

final class Sockets
{
    private $serverSocket;
    private $additionalSockets;

    /**
     * @var null|Socket
     */
    private $apiSocket;

    /**
     * @var null|Socket
     */
    private $httpsRedirectorSocket;

    public function __construct(Socket $serverSocket, ?Socket $apiSocket = null, ?Socket $httpsRedirectorSocket = null, Socket ...$additionalSockets)
    {
        $this->serverSocket = $serverSocket;
        $this->apiSocket = $apiSocket;
        $this->httpsRedirectorSocket = $httpsRedirectorSocket;
        $this->additionalSockets = $additionalSockets;
    }

    public function changeServerSocket(Socket $socket): void
    {
        $this->serverSocket = $socket;
    }

    public function getServerSocket(): Socket
    {
        return $this->serverSocket;
    }

    public function getApiSocket(): Socket
    {
        Assertion::isInstanceOf($this->apiSocket, Socket::class, 'API Socket is not defined.');

        return $this->apiSocket;
    }

    public function hasApiSocket(): bool
    {
        return $this->apiSocket instanceof Socket;
    }

    public function getHttpsRedirectorSocket(): Socket
    {
        Assertion::isInstanceOf($this->httpsRedirectorSocket, Socket::class, 'HTTPS redirector Socket is not defined.');

        return $this->httpsRedirectorSocket;
    }

    public function hasHttpsRedirectorSocket(): bool
    {
        return $this->httpsRedirectorSocket instanceof Socket;
    }

    public function disableApiSocket(): void
    {
        $this->apiSocket = null;
    }

    public function changeApiSocket(Socket $socket): void
    {
        $this->apiSocket = $socket;
    }

    /**
     * Get sockets in order:
     * - first server socket
     * - next if defined api socket
     * - rest of sockets.
     */
    public function getAll(): Generator
    {
        yield $this->serverSocket;

        if ($this->hasApiSocket()) {
            yield $this->apiSocket;
        }

        if ($this->hasHttpsRedirectorSocket()) {
            yield $this->httpsRedirectorSocket;
        }

        yield from $this->additionalSockets;
    }
}
