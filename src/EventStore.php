<?php

namespace Reoring\Phpes;

class EventStore
{
  use EventAssertion;

  private $backend;
  private $dispatcher;

  public function __construct(EventStoreBackendInterface $backend, EventDispatcher $dispatcher)
  {
      $this->backend = $backend;
      $this->dispatcher = $dispatcher;
  }

  public function append(string $streamName, array $unstoredEvents)
  {
      $this->assertStreamName($streamName);
      $this->assertUnstoredEvents($unstoredEvents);

      $nextIdentities = $this->nextIdentities($streamName, count($unstoredEvents));
      $events = $this->identityEvents($streamName, $nextIdentities, $unstoredEvents);

      $this->backend->save($events);

      foreach ($events as $event) {
          $this->dispatcher->dispatch($event);
      }
  }

  public function events(int $since = 0): array
  {
      return $this->backend->events($since);
  }

  public function stream(string $streamName, int $since = 0): array
  {
      return $this->backend->stream($streamName, $since);
  }

  private function nextIdentities(string $streamName, int $size)
  {
      $nextIdentity = $this->backend->nextIdentity($streamName);
      $seq = [];

      for ($i = 0; $i < $size; $i++) {
          $seq[] = new NextIdentity($nextIdentity->eventId() + $i, $nextIdentity->streamVersion() + $i);
      }

      return $seq;
  }

  /**
   * Identity events
   *
   * @param $streamName string
   * @param $nextIdentities array[NextIdentity]
   * @param $unstoredEvents array[UnstoredEvent]
   * @return array[Event]
   */
  private function identityEvents(string $streamName, array $nextIdentities, array $unstoredEvents): array
  {
      $seq = [];
      $zippedMap = array_map(null, $unstoredEvents, $nextIdentities);

      foreach ($zippedMap as $map) {
          $unstoredEvent = $map[0];
          $nextIdentity = $map[1];
          $seq[] = $unstoredEvent->identity($streamName, $nextIdentity);
      }

      return $seq;
  }
}
