<?php

declare(strict_types=1);

namespace K911\Swoole\Bridge\Symfony\Messenger;

use K911\Swoole\Server\HttpServer;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;

final class SwooleServerPipeSender implements SenderInterface
{
    private $httpServer;

    public function __construct(HttpServer $httpServer)
    {
        $this->httpServer = $httpServer;
    }

    /**
     * {@inheritdoc}
     */
    public function send(Envelope $envelope): Envelope
    {
        /** @var null|SentStamp $sentStamp */
        $sentStamp = $envelope->last(SentStamp::class);
        $alias = null === $sentStamp ? 'swoole-process' : $sentStamp->getSenderAlias() ?? $sentStamp->getSenderClass();

        $this->httpServer->broadcastMessage($envelope->with(new ReceivedStamp($alias)));

        return $envelope;
    }
}
