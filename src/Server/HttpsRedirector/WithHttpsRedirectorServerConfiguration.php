<?php

declare(strict_types=1);

namespace K911\Swoole\Server\HttpsRedirector;

use K911\Swoole\Server\Config\Sockets;
use K911\Swoole\Server\Configurator\ConfiguratorInterface;
use K911\Swoole\Server\RequestHandler\RequestHandlerInterface;
use Swoole\Http\Server;

final class WithHttpsRedirectorServerConfiguration implements ConfiguratorInterface
{
    private $sockets;
    private $requestHandler;

    public function __construct(Sockets $sockets, RequestHandlerInterface $requestHandler)
    {
        $this->sockets = $sockets;
        $this->requestHandler = $requestHandler;
    }

    public function configure(Server $server): void
    {
        if (!$this->sockets->hasHttpsRedirectorSocket()) {
            return;
        }

        $httpRedirectorPort = $this->sockets->getHttpsRedirectorSocket()->port();
        foreach ($server->ports as $port) {
            if ($port->port === $httpRedirectorPort) {
                $port->on('request', [$this->requestHandler, 'handle']);

                return;
            }
        }
    }
}
