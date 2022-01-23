<?php

declare(strict_types=1);

namespace K911\Swoole\Bridge\Symfony\HttpFoundation;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ResponseExposeSwooleRequestAttributesEventListener implements EventSubscriberInterface
{
    public function __construct()
    {
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $response = $event->getResponse();
        $response->headers->add([
            'X-Worker-Id' => $event->getRequest()->server->get('WORKER_ID'),
            'X-Request-Fd' => $event->getRequest()->server->get('REQUEST_FD')
        ]);

    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse', -128],
        ];
    }
}
