<?php

declare(strict_types=1);

namespace K911\Swoole\Server\Process;

use Swoole\Server;

final class NoOpPipeHandler implements PipeHandlerInterface
{
    public function handle(Server $server, int $fromWorkerId, mixed $message): void
    {
        // noop
    }
}
