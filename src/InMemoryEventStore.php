<?php

namespace Reoring\Phpes;

class InMemoryEventStore implements EventStoreInterface
{
    private $store = [];

    public function append(string $streamName, Event $event)
    {
        $this->store[$streamName] = [];
        $this->store[$streamName][] = $event;
    }

    public function stream(string $streamName): array
    {
      return $this->store[$streamName];
    }

    public function count(string $streamName): int
    {
        return count($this->store[$streamName]);
    }
}
