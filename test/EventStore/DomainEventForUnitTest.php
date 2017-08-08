<?php

namespace Reoring\Phpes\Test\EventStore;

class DomainEventForUnitTest implements \JsonSerializable
{
    private $name;
    private $age;

    public function __construct($name, $age)
    {
        $this->name = $name;
        $this->age = $age;
    }

    public static function createFromJson(string $json)
    {
        $obj = json_decode($json);
        return new self($obj->name, $obj->age);
    }

    public function jsonSerialize() {
        return ["name" => $this->name, "age" => $this->age];
    }
}
