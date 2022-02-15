<?php

declare(strict_types=1);

namespace K911\Swoole\MessageBus;

use Assert\Assertion;
use K911\Swoole\Server\Process\PipeHandlerInterface;
use Swoole\Server;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class SwooleMessageBusPipeHandler implements PipeHandlerInterface
{
    private $bus;
    private $decorated;

    public function __construct(SwooleMessageBus $bus, ?PipeHandlerInterface $decorated = null)
    {
        $this->bus = $bus;
        $this->decorated = $decorated;
    }

    public function handle(Server $server, int $fromWorkerId, mixed $message): void
    {
        if(is_array($message)) {
            list($topic, $data) = $message;
            $this->bus->injectLocalEvent($topic, $data);
        }

        if ($this->decorated instanceof PipeHandlerInterface) {
            $this->decorated->handle($server, $fromWorkerId, $message);
        }
    }
}
