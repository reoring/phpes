<?php

namespace Reoring\Phpes;

trait EventAssertion
{
  private $streamNameMax = 512;
  private $eventTypeMax = 512;

  public function assertUnstoredEvent(UnstoredEvent $unstoredEvent): void
  {
      $this->assertStreamVersion($unstoredEvent->expectedStreamVersion());
      $this->assertEventType($unstoredEvent->eventType());
  }

  public function assertUnstoredEvents(array $unstoredEvents): void
  {
      foreach ($unstoredEvents as $unstoredEvent) {
          $this->assertUnstoredEvent($unstoredEvent);
      }
  }

  public function assertEventId(int $eventId): void
  {
      assert($eventId > 0, "eventId must be > 0");
  }

  public function assertStreamVersion(int $streamVersion): void
  {
      assert($streamVersion > 0, "streamVersion must be > 0");
  }

  public function assertStreamName(string $streamName): void
  {
      assert($streamName->nonEmpty(), "streamName must not be empty");
      assert($streamName->length() <= $this->streamNameMax, "streamName length must be <= $this->streamNameMax");
  }

  public function assertEventType(string $eventType): void
  {
      assert($eventType->nonEmpty(), "eventType must be non-empty");
      assert($eventType->length() <= $this->eventTypeMax, "eventType length must be <= $this->eventTypeMax");
  }
}
