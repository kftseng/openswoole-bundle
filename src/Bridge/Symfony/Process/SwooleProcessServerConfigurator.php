<?php

declare(strict_types=1);

namespace K911\Swoole\Bridge\Symfony\Process;

use App\Process\TestProcess;
use K911\Swoole\Server\Configurator\ConfiguratorInterface;
use Swoole\Http\Server;

class SwooleProcessServerConfigurator implements ConfiguratorInterface
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
                $process->run($swooleProcess);
            });

            $server->addProcess($swooleProcess);

        }
    }
}
