<?php

declare(strict_types=1);

namespace K911\Swoole\Server\Process;

use Swoole\Process;

interface ProcessInterface
{
    public function run() : void;
}
