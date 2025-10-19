<?php

namespace DesignPatterns\Creational;

class DatabaseConnectionPool
{
    private array $availableConnections = [];
    private array $inUseConnections = [];

    /**
    * If no availavle connections, we create new ones
    */
    public function get(): DatabaseConnection
    {
        if (count($this->availableConnections) === 0) {
            $connection = DatabaseConnection::getInstance();
            $this->inUseConnections[] = $connection;
            return $connection;
        } else {
            $connection = array_pop($this->availableConnections);
            $this->inUseConnections[] = $connection;
            return $connection;
        }
    }

    public function release(DatabaseConnection $connection): void
    {
        $index = array_search($connection, $this->inUseConnections, true);
        if ($index !== false) {
            unset($this->inUseConnections[$index]);
            $this->availableConnections[] = $connection;
        }
    }
}


class DatabaseConnection
{
    private static ?DatabaseConnection $instance = null;

    private function __construct()
    {
        // private constructor to prevent direct instantiation
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query(string $query): void
    {
        // Execute the given query...
        echo "Executing query: $query\n";
    }
}

# client code example

$pool = new DatabaseConnectionPool();

// Get a connection from the pool and use it
$connection = $pool->get();
$connection->query("SELECT * FROM users");
$pool->release($connection);
