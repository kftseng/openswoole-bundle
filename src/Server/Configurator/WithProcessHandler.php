<?php

declare(strict_types=1);

namespace K911\Swoole\Server\Configurator;

use K911\Swoole\Server\Configurator\ConfiguratorInterface;
use K911\Swoole\Server\HttpServer;
use K911\Swoole\Server\Process\PipeHandlerInterface;
use Swoole\Event;
use Swoole\Http\Server;
use Swoole\Process;
use Swoole\Timer;

class WithProcessHandler implements ConfiguratorInterface
{
    private $httpServer;
    private $processes;
    private $handler;

    public function __construct(iterable $processes, PipeHandlerInterface $handler, HttpServer $httpServer) {
        $this->httpServer = $httpServer;
        $this->processes = $processes;
        $this->handler = $handler;
    }

    public function configure(Server $server): void
    {
        foreach($this->processes as $processDefinitionInstance) {
            $processDefinitionName = get_class($processDefinitionInstance);
            $processDefinitionFilename = (new \ReflectionClass($processDefinitionName))->getFileName();
            $processDefinitionSysVId = ftok($processDefinitionFilename, "1");

            $userWorker = new Process(function(Process $userWorker) use ($processDefinitionInstance, $server) {
                \Swoole\Coroutine\run(function() use ($server, $userWorker) {
                    while ($data = $userWorker->pop()) {
                        $data = unserialize($data);
                        //TODO: set real worker id
                        $this->handler->handle($server, -1, $data);
                    }
                });

                $processDefinitionInstance->run();
            });

            $userWorker->useQueue($processDefinitionSysVId);
            $userWorker->name($processDefinitionName);
            $server->addProcess($userWorker);

            $this->httpServer->addUserWorker($userWorker);
        }
    }
}
