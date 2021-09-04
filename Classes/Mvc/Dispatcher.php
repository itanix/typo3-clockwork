<?php
namespace Itanix\Clockwork\Mvc;

use Clockwork\Support\Vanilla\Clockwork;
use TYPO3\CMS\Extbase\Mvc\Exception\InfiniteLoopException;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidControllerException;
use TYPO3\CMS\Extbase\Mvc\Exception\NoSuchControllerException;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;

class Dispatcher extends \TYPO3\CMS\Extbase\Mvc\Dispatcher
{
    /**
     * Dispatches a request to a controller and initializes the security framework.
     *
     * @param RequestInterface $request The request to dispatch
     * @param ResponseInterface $response The response, to be modified by the controller
     * @throws InfiniteLoopException
     * @throws InvalidControllerException
     * @throws NoSuchControllerException
     */
    public function dispatch(RequestInterface $request, ResponseInterface $response)
    {
        $clockwork = Clockwork::instance();

        if(!$clockwork) {
            parent::dispatch($request, $response);
            return;
        }

        $this->resolveController($request);

        $controllerObjectName = $request->getControllerObjectName();
        $controllerActionName = $request->getControllerActionName() . 'Action';

        $eventName = $controllerObjectName . '::' . $controllerActionName;

        $clockwork->event($eventName)->run(function() use($request, $response) {
            parent::dispatch($request, $response);
        });
    }
}