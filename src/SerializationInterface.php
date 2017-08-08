<?php

namespace Reoring\Phpes;

interface SerializationInterface
{
    public function serialize($object): string;
    public function deserialize(string $eventType, string $data);
}
