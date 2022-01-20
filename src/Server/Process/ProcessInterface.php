<?php

declare(strict_types=1);

namespace K911\Swoole\Server\Process;

use Swoole\Process;
use Swoole\Server;

interface ProcessInterface
{
    public function run(Process $process, Server $server) : void;

    public function getName() : string;
}
