<?php

namespace Reoring\Phpes;

class PdoEventStoreBackend implements EventStoreBackendInterface
{
    private $connection = null;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function nextIdentity(string $streamName): NextIdentity
    {
        $query = 'SELECT (SELECT COALESCE(MAX(event_id),0)+1 FROM eventstore) AS event_id, ';
        $query .= '(SELECT COALESCE(MAX(stream_version),0)+1 FROM eventstore WHERE stream_name=:stream_name) AS stream_id';

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':stream_name', $streamName, \PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return new NextIdentity($result['event_id'], $result['stream_id']);
    }

    public function save(array $events)
    {
        $insertSql = 'INSERT INTO eventstore VALUES (:event_id, :stream_name, :stream_version, :event_type, :data)';

        foreach ($events as $event) {
            $stmt = $this->connection->prepare($insertSql);
            $stmt->bindValue(':event_id', $event->eventId, \PDO::PARAM_INT);
            $stmt->bindValue(':stream_name', $event->streamName, \PDO::PARAM_STR);
            $stmt->bindValue(':stream_version', $event->streamVersion, \PDO::PARAM_INT);
            $stmt->bindValue(':event_type', $event->eventType, \PDO::PARAM_STR);
            $stmt->bindValue(':data', $event->data, \PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    public function events(int $since): array
    {
        $query = 'SELECT * FROM eventstore WHERE event_id >= :since ORDER BY event_id ASC';

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':since', $since, \PDO::PARAM_INT);
        $stmt->execute();

        return $this->convertRawEvent($stmt);
    }

    public function stream(string $streamName, int $since): array
    {
        $query = 'SELECT * FROM eventstore ';
        $query .= 'WHERE stream_name = :stream_name AND stream_version >= :since ';
        $query .= 'ORDER BY event_id ASC';

        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':stream_name', $streamName, \PDO::PARAM_STR);
        $stmt->bindParam(':since', $since, \PDO::PARAM_INT);
        $stmt->execute();

        return $this->convertRawEvent($stmt);
    }


    private function convertRawEvent(\PDOStatement $statement): array
    {
        $seq = [];

        foreach ($statement->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $seq[] = new Event($row['event_id'],
                               $row['stream_name'],
                               $row['stream_version'],
                               $row['event_type'],
                               $row['data']);
        }

        return $seq;
    }
}
