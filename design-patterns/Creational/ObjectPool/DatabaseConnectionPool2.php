<?php

namespace DesignPatterns\Creational;

// The Reusable object
class DatabaseConnection
{
    private string $id;
    private bool $inUse = false;

    public function __construct()
    {
        $this->id = uniqid('conn_');
        echo "Creating new DatabaseConnection: " . $this->id . "\n";
        // Simulate a costly connection setup
        sleep(1); 
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isInUse(): bool
    {
        return $this->inUse;
    }

    public function setInUse(bool $inUse): void
    {
        $this->inUse = $inUse;
    }

    public function query(string $sql): string
    {
        return "Executing query '{$sql}' with connection {$this->id}\n";
    }
}

// The Object Pool
class ConnectionPool
{
    private array $availableConnections = [];
    private array $inUseConnections = [];
    private int $maxPoolSize;

    public function __construct(int $maxPoolSize = 5)
    {
        $this->maxPoolSize = $maxPoolSize;
    }

    public function getConnection(): DatabaseConnection
    {
        if (!empty($this->availableConnections)) {
            $connection = array_pop($this->availableConnections);
            $connection->setInUse(true);
            $this->inUseConnections[] = $connection;
            echo "Reusing existing connection: " . $connection->getId() . "\n";
            return $connection;
        }

        if (count($this->inUseConnections) < $this->maxPoolSize) {
            $connection = new DatabaseConnection();
            $connection->setInUse(true);
            $this->inUseConnections[] = $connection;
            return $connection;
        }

        throw new Exception("Maximum pool size reached. No available connections.");
    }

    public function releaseConnection(DatabaseConnection $connection): void
    {
        $key = array_search($connection, $this->inUseConnections, true);
        if ($key !== false) {
            unset($this->inUseConnections[$key]);
            $connection->setInUse(false);
            $this->availableConnections[] = $connection;
            echo "Releasing connection: " . $connection->getId() . "\n";
        }
    }
}

// Client code example
$pool = new ConnectionPool(3);

try {
    $conn1 = $pool->getConnection();
    echo $conn1->query("SELECT * FROM users");

    $conn2 = $pool->getConnection();
    echo $conn2->query("INSERT INTO products VALUES (...)");

    $pool->releaseConnection($conn1);

    $conn3 = $pool->getConnection(); // This will reuse conn1
    echo $conn3->query("UPDATE orders SET status = 'shipped'");

    $conn4 = $pool->getConnection(); // New connection
    echo $conn4->query("DELETE FROM temp_data");

    // This will throw an exception as max pool size is 3
    // $conn5 = $pool->getConnection(); 

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

?>
