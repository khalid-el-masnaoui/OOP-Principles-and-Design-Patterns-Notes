<?php

namespace DesignPatterns\Creational;

class Logger
{
    private static ?Logger $instance = null; // Stores the single instance
    private string $logFile; // Path to the log file

    // Private constructor prevents direct instantiation
    private function __construct(string $logFile = 'application.log')
    {
        $this->logFile = $logFile;
        // Ensure the log file is writable
        if (!is_writable(dirname($this->logFile)) && !mkdir(dirname($this->logFile), 0777, true)) {
            throw new Exception("Log directory not writable: " . dirname($this->logFile));
        }
    }

    // Prevents cloning of the instance
    private function __clone() {}

    // Prevents unserialization of the instance
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }

    // Public static method to get the single instance
    public static function getInstance(string $logFile = 'application.log'): Logger
    {
        if (self::$instance === null) {
            self::$instance = new self($logFile);
        }
        return self::$instance;
    }

    // Method to write a message to the log file
    public function log(string $message, string $level = 'INFO'): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] [$level] $message" . PHP_EOL;
        file_put_contents($this->logFile, $logEntry, FILE_APPEND);
    }
}

// Usage example:
Logger::getInstance()->log("Application started.", "INFO");
Logger::getInstance()->log("User 'john_doe' logged in.", "DEBUG");
Logger::getInstance('custom.log')->log("This message goes to custom.log", "WARNING"); // Will still return the same instance, but the log file might be overridden if called with a different name.
                                                                                // For a true singleton with a fixed log file, remove the $logFile parameter from getInstance or handle it differently.
