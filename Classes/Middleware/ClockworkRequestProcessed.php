<?php
namespace Itanix\Clockwork\Middleware;

use Clockwork\Support\Vanilla\Clockwork;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ClockworkRequestProcessed implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var Clockwork $clockwork */
        $clockwork = Clockwork::instance();

        $response = $handler->handle($request);

        return $clockwork->usePsrMessage($request, $response)->requestProcessed();
    }
}