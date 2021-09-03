<?php

namespace Itanix\Clockwork\Middleware;

use Clockwork\Support\Vanilla\Clockwork;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\JsonResponse;

class ClockworkRestApiEndpoint implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if($request->getUri()->getPath() === '/__clockwork')
        {
            $clockworkRequest = $request->getQueryParams()['request'] ?? null;

            /** @var Clockwork $clockwork */
            $clockwork = Clockwork::instance();

            return new JsonResponse($clockwork->getMetadata($clockworkRequest));
        }

        return $handler->handle($request);
    }
}