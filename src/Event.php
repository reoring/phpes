<?php

namespace Reoring\Phpes;

class Event
{
    private $eventId;
    private $streamName;
    private $streamVersion;
    private $eventType;
    private $data;

    public function __construct(int $eventId,
                                string $streamName,
                                int $streamVersion,
                                string $eventType,
                                $data)
    {
        $this->eventId = $eventId;
        $this->streamName = $streamName;
        $this->streamVersion = $streamVersion;
        $this->eventType = $eventType;
        $this->data = $data;
    }

    public function __get(string $attributeName)
    {
        return $this->$attributeName;
    }
}
