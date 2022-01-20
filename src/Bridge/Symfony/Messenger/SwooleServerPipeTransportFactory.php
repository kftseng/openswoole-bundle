<?php

declare(strict_types=1);

namespace K911\Swoole\Bridge\Symfony\Messenger;

use K911\Swoole\Server\HttpServer;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportFactoryInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

final class SwooleServerPipeTransportFactory implements TransportFactoryInterface
{
    private $server;

    public function __construct(HttpServer $server)
    {
        $this->server = $server;
    }

    public function createTransport(string $dsn, array $options, SerializerInterface $serializer): TransportInterface
    {
        return new SwooleServerPipeTransport(
            new SwooleServerPipeReceiver(),
            new SwooleServerPipeSender($this->server)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function supports(string $dsn, array $options): bool
    {
        return 0 === \mb_strpos($dsn, 'swoole://pipe');
    }
}
