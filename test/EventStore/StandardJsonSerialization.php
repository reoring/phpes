<?php

namespace Reoring\Phpes\Test\EventStore;

Use Reoring\Phpes\SerializationInterface;

class StandardJsonSerialization implements SerializationInterface
{
    public function serialize($object): string
    {
        return json_encode($object);
    }

    public function deserialize(string $eventType, string $data)
    {
        return json_decode($data);
    }
}
