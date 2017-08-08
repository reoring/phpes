<?php

namespace Reoring\Phpes;

class InMemoryEventStoreBackend implements EventStoreBackendInterface
{
    private $eventId = 0;
    private $identities = [];
    private $storage = [];

    public function nextIdentity(string $streamName): NextIdentity
    {
        if (!array_key_exists($streamName, $this->identities)) {
            $this->identities[$streamName] = 0;
        }

        $this->identities[$streamName]++;
        $this->eventId++;

        return new NextIdentity($this->eventId, $this->identities[$streamName]);
    }

    public function save(array $events)
    {
        foreach ($events as $event) {
            if (!array_key_exists($event->streamName, $this->storage)) {
                $this->storage[$event->streamName] = [];
            }

            $this->storage[$event->streamName][] = $event;
        }
    }

    public function events(int $since)
    {
        $seq = [];
        $filteredSeq = [];

        foreach ($this->storage as $streamName => $value) {
            foreach ($this->storage[$streamName] as $event) {
                $seq[] = $event;
            }
        }

        foreach ($seq as $event) {
            if ($event->eventId > $since) {
                $filteredSeq[] = $event;
            }
        }

        return $filteredSeq;
    }

    public function stream(string $streamName, int $since)
    {
        $seq = [];

        if (!array_key_exists($streamName, $this->storage)) {
            return [];
        }

        foreach ($this->storage[$streamName] as $event) {
            if ($event->streamVersion > $since) {
                $seq[] = $event;
            }
        }

        return $seq;
    }
}
