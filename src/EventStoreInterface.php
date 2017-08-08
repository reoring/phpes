<?php

namespace Reoring\Phpes;

interface EventStoreInterface
{
  public function append(string $streamName, Event $event);
  public function stream(string $steranName): array;
  public function count(string $streamName): int;
}
