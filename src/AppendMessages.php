<?php

namespace Reoring\Phpes;

class AppendMessages
{
    private $unmutatedStreamVersion;
    private $messages = [];

    public function __construct(int $unmutatedStreamVersion, SerializationInterface $serializer)
    {
        $this->unmutatedStreamVersion = $unmutatedStreamVersion;
        $this->serializer = $serializer;
    }

    public function add($event)
    {
        $this->unmutatedStreamVersion++;
        $this->messages[] = new UnstoredEvent($this->unmutatedStreamVersion,
                                              get_class($event),
                                              $this->serializer->serialize($event));
    }

    public function toSeq()
    {
      return $this->messages;
    }
}
