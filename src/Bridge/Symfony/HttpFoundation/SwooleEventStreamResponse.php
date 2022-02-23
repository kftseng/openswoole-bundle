<?php

declare(strict_types=1);

namespace K911\Swoole\Bridge\Symfony\HttpFoundation;

class SwooleEventStreamResponse extends SwooleStreamedResponse
{
    public function __construct()
    {
        parent::__construct(200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no'
        ]);
    }

    public function pushEvent(?string $event, ?string $data): bool
    {
        $message = "";

        if($event)
            $message = "event: " . $event . "\n";

        if($data)
            $message .= "data: " . $data . "\n";

        $message .= "\n";

        return $this->push($message);
    }
}
