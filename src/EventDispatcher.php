<?php

namespace Reoring\Phpes;

/**
 * EventDispatcher interface
 */
 interface EventDispatcher
 {
     /**
      * @param Event $event
      */
     public function dispatch(Event $event): void;
 }
