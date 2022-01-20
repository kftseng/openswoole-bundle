<?php

declare(strict_types=1);

namespace K911\Swoole\Server\Configurator;

use K911\Swoole\Server\HttpServerConfiguration;
use K911\Swoole\Server\Process\PipeHandlerInterface;
use Swoole\Http\Server;

final class WithPipeHandler implements ConfiguratorInterface
{
    private $handler;
    private $configuration;

    public function __construct(PipeHandlerInterface $handler, HttpServerConfiguration $configuration)
    {
        $this->handler = $handler;
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function configure(Server $server): void
    {
        $server->on('PipeMessage', [$this->handler, 'handle']);
    }
}
