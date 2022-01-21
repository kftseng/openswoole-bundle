<?php

declare(strict_types=1);

namespace K911\Swoole\Bridge\Symfony\HttpFoundation;

use K911\Swoole\Server\HttpServer;
use Swoole\Http\Response as SwooleResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

final class ResponseProcessor implements ResponseProcessorInterface
{
    protected $httpServer;

    public function __construct(HttpServer $httpServer) {
        $this->httpServer = $httpServer;
    }

    /**
     * {@inheritdoc}
     */
    public function process(HttpFoundationResponse $httpFoundationResponse, SwooleResponse $swooleResponse): void
    {
        if ($httpFoundationResponse instanceof SwooleStreamedResponse) {
            $httpFoundationResponse->run($this->httpServer->getServer(), $swooleResponse);

        } elseif ($httpFoundationResponse instanceof BinaryFileResponse) {
            $swooleResponse->sendfile($httpFoundationResponse->getFile()->getRealPath());
        } else {
            $swooleResponse->end($httpFoundationResponse->getContent());
        }
    }
}
