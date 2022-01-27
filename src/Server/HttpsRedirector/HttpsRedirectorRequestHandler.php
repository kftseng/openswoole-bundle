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
        if (isset($request->header['host'])) {
            $url = "https://" . $request->header['host'] . $request->server['path_info'];

            if (!empty($request->get))
                $url .= "?" . http_build_query($request->get);

            $response->header('Location', $url);
            $response->status(301);
        } else {
            $response->status(500);
        }

        $response->end();
    }
}
