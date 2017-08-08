<?php

namespace Reoring\Phpes;

class UnstoredEvent
{
    private $expectedStreamVersion;
    private $eventType;
    private $data;

    public function __construct(int $expectedStreamVersion, string $eventType, string $data)
    {
        $this->expectedStreamVersion = $expectedStreamVersion;
        $this->eventType = $eventType;
        $this->data = $data;
    }

    public function identity(string $streamName, NextIdentity $identity): Event
    {
        return new Event($identity->eventId(), $streamName, $identity->streamVersion(), $this->eventType, $this->data);
    }

    public function expectedStreamVersion(): int
    {
        return $this->expectedStreamVersion;
    }

    public function eventType(): string
    {
        return $this->eventType;
    }

    public function data()
    {
        return $this->data;
    }
}
