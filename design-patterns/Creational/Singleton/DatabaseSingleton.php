<?php

namespace DesignPatterns\Creational;

class Database
{
    private static ?Database $instance = null; // Stores the single instance of the class
    private PDO $connection; // Stores the database connection object

    // Private constructor to prevent direct instantiation
    private function __construct()
    {
        $host = 'localhost';
        $dbName = 'your_database_name';
        $user = 'your_username';
        $password = 'your_password';

        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$dbName", $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Prevents cloning of the instance
    private function __clone() {}

    // Prevents unserialization of the instance
    private function __wakeup() {}

    // Public static method to get the single instance of the class
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Method to get the actual PDO database connection
    public function getConnection(): PDO
    {
        return $this->connection;
    }
}

// Usage example:
$db = Database::getInstance();
$pdo = $db->getConnection();

// You can now use $pdo for your database operations
// For example:
// $stmt = $pdo->query("SELECT * FROM users");
// $users = $stmt->fetchAll();
// print_r($users);

// Subsequent calls to getInstance() will return the same instance
$anotherDbInstance = Database::getInstance();
// $db and $anotherDbInstance refer to the same object
var_dump($db === $anotherDbInstance); // Output: bool(true)

?>
