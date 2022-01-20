<?php

declare(strict_types=1);

namespace K911\Swoole\Server\Configurator;

use K911\Swoole\Server\Configurator\ConfiguratorInterface;
use Swoole\Http\Server;

class WithProcessHandler implements ConfiguratorInterface
{
    protected $processes;

    public function __construct(iterable $processes) {
        $this->processes = $processes;
    }

    public function configure(Server $server): void
    {
        foreach($this->processes as $process) {
            $swooleProcess = new \Swoole\Process(function(\Swoole\Process $swooleProcess) use ($process) {
                $swooleProcess->name($process->getName());
                $process->run();
            });

            $server->addProcess($swooleProcess);

        }
    }
}
