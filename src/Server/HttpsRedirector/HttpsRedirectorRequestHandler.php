<?php

declare(strict_types=1);

namespace K911\Swoole\Server\HttpsRedirector;

use K911\Swoole\Server\RequestHandler\RequestHandlerInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;

final class HttpsRedirectorRequestHandler implements RequestHandlerInterface
{
    public function handle(Request $request, Response $response): void
    {
        if(isset($request->header['host'])) {
            $response->header('Location', 'https://' . $request->header['host'] . $request->server['path_info']);
            $response->status(301);
        } else {
            $response->status(500);
        }

        $response->end();
    }
}
