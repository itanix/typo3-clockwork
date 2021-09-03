<?php
namespace Itanix\Clockwork\EventDispatcher;

use Clockwork\Helpers\Serializer;
use Clockwork\Helpers\StackTrace;
use Clockwork\Support\Vanilla\Clockwork;
use Psr\EventDispatcher\StoppableEventInterface;
use TYPO3\CMS\Core\Utility\PathUtility;

class EventDispatcher extends \TYPO3\CMS\Core\EventDispatcher\EventDispatcher
{
    public function dispatch(object $event)
    {
        // If the event is already stopped, nothing to do here.
        if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
            return $event;
        }

        $clockwork = Clockwork::instance();

        if($clockwork)
        {
            $clockwork->addEvent(get_class($event), [], microtime(true), [
                'listeners' => $this->findListenersFor($event),
                'trace'     => (new Serializer)->trace(StackTrace::get())
            ]);
        }

        return parent::dispatch($event);
    }

    protected function findListenersFor($event)
    {
        return array_filter(array_map(function ($listener) {
            if (is_string($listener)) {
                return $listener;
            } elseif (is_array($listener) && count($listener) == 2) {
                if (is_object($listener[0])) {
                    return get_class($listener[0]) . '@' . $listener[1];
                } else {
                    return $listener[0] . '::' . $listener[1];
                }
            } elseif ($listener instanceof \Closure) {
                $listener = new \ReflectionFunction($listener);

                if (strpos($listener->getNamespaceName(), 'Clockwork\\') === 0) { // skip our own listeners
                    return;
                }

                $filename = PathUtility::getRelativePathTo($listener->getFileName());
                $startLine = $listener->getStartLine();
                $endLine = $listener->getEndLine();

                return "Closure ({$filename}:{$startLine}-{$endLine})";
            }
        }, (array)$this->listenerProvider->getListenersForEvent($event)));
    }
}