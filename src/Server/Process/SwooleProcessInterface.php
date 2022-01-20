<?php

declare(strict_types=1);

namespace K911\Swoole\Server\Process;

interface SwooleProcessInterface
{
    public function run(\Swoole\Process $process) : void;

    public function getName() : string;
}
