<?php

namespace Reoring\Phpes\Test\EventStore;

use PHPUnit\Framework\TestCase;

Use Reoring\Phpes\InMemoryEventStore;
Use Reoring\Phpes\Event;
Use Reoring\Phpes\UnstoredEvent;
Use Reoring\Phpes\AppendMessages;

/**
 * BasicEventTest class
 */
class BasicEventTest extends TestCase
{
    public function testAppendEventToStream(): void
    {
        $es = new InMemoryEventStore();
        $es->append("hoge", new Event(1, "hoge", 1, "Added", "data"));

        $this->assertEquals(1, $es->count("hoge"));
    }

    public function testAppendMessages(): void
    {
        $domainEvent = new DomainEventForUnitTest("name", 34);

        $messages = new AppendMessages(0, new StandardJsonSerialization);
        $messages->add(new Event(1, "hoge", 1, "Added", $domainEvent));

        $storedDomainEvent = $messages->toSeq()[0]->data();

        $this->assertEquals(
          DomainEventForUnitTest::createFromJson($storedDomainEvent),
          $domainEvent
        );
    }
}
