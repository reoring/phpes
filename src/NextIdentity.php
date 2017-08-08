<?php

namespace Reoring\Phpes;

class NextIdentity
{
    private $eventId;
    private $streamVersion;

    public function __construct(int $eventId, int $streamVersion)
    {
        $this->eventId = $eventId;
        $this->streamVersion = $streamVersion;
    }

    public function eventId(): int
    {
        return $this->eventId;
    }

    public function streamVersion(): int
    {
        return $this->streamVersion;
    }
}
