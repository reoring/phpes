<?php

namespace Reoring\Phpes;

interface EventStoreBackendInterface
{
    public function nextIdentity(string $streamName);
    public function save(array $events);
    public function events(int $since);
    public function stream(string $streamName, int $since);
}
