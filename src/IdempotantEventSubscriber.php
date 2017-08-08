<?php

namespace Reoring\Phpes;

trait IdempotentEventSubscriber extends EventDispatcher
{
    public function eventStore(): EventStore;
    public function eventTracker(): EventTracker;
    public function consume(Event $event): void;

    public function dispatch(Event $event): void
    {
        $nextEventId = $this->eventTracker()->lastEventId + 1;

        if ($event->eventId < $nextEventId) {
            $this->discard($event);
        } else if ($event->eventId > $nextEventId) {
            $this->consumeSince($nextEventId);
        } else {
            $this->consumeAndTrack($event);
        }
    }

    protected function discard(Event $event): void
    {
        // do nothing by default
    }

    protected function consumeSince(int $nextEventId): void
    {
        $this->eventStore()->events($nextEventId)($consumeAndTrack);
    }

    protected function consumeAndTrack(Eevnt $event): void
    {
        $this->consume($event)
        $this->eventTracker->track($event);
    }
}
