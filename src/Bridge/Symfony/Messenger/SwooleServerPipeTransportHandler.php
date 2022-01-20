<?php

declare(strict_types=1);

namespace K911\Swoole\Bridge\Symfony\Messenger;

use Assert\Assertion;
use K911\Swoole\Server\Process\PipeHandlerInterface;
use Swoole\Server;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class SwooleServerPipeTransportHandler implements PipeHandlerInterface
{
    private $bus;
    private $decorated;

    public function __construct(MessageBusInterface $bus, ?PipeHandlerInterface $decorated = null)
    {
        $this->bus = $bus;
        $this->decorated = $decorated;
    }

    public function handle(Server $server, int $fromWorkerId, mixed $message): void
    {
        Assertion::isInstanceOf($message, Envelope::class);
        /* @var $data Envelope */

        $this->bus->dispatch($message);

        if ($this->decorated instanceof PipeHandlerInterface) {
            $this->decorated->handle($server, $fromWorkerId, $message);
        }
    }
}
