<?php

namespace Reoring\Phpes;

use Reoring\Phpes\Event;
use Reoring\Phpes\EventStore;
use Reoring\Phpes\EventDispatcher;

/**
 * RootEventDispatcher
 */
class RootEventDispatcher implements EventDispatcher
{
    /**
     * @var array[EventDispatcher] $subscribers
     */
    private $subscribers = [];

     /**
      * @param Event $event
      */
    public function dispatch(Event $event): void
    {
        foreach ($this->subscribers as $subscriber) {
            $subscriber->dispatch($event);
        }
    }

     /**
      * @param EventDispatcher $subscriber
      */
    public function register($subscriber): void
    {
        $this->subscribers[] = $subscriber;
    }
}
