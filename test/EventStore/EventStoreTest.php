<?php

namespace Reoring\Phpes\Test\EventStore;

use PHPUnit\Framework\TestCase;

Use Reoring\Phpes\EventStore;
Use Reoring\Phpes\InMemoryEventStoreBackend;

Use Reoring\Phpes\UnstoredEvent;

/**
 * EventStoreTest class
 */
class EventStoreTest extends TestCase
{
    public function testAppendingEvents(): void
    {
        $domainEvent = new DomainEventForUnitTest("name", 34);

        $es = new EventStore(new InMemoryEventStoreBackend);
        $es->append("MyEventStreamName", [new UnstoredEvent(1, "event_type", json_encode($domainEvent))]);
        $es->append("MyAnotherEventStreamName", [new UnstoredEvent(1, "event_type", json_encode($domainEvent))]);

        // Count by each stream
        $this->assertCount(1, $es->stream("MyEventStreamName"));
        $this->assertCount(1, $es->stream("MyAnotherEventStreamName"));

        // Total events
        $this->assertCount(2, $es->events());

        // There is not exist
        $this->assertCount(0, $es->stream("StreamNotExists"));
    }
}
