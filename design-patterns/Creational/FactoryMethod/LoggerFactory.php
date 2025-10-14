<?php

namespace DesignPatterns\Creational;

/**
 * Base factory class contains a factory method and some business logic
 */
interface LoggerFactory
{
    public function createLogger(): Logger;
}


/**
 * Concrete factories implement that factory interface to produce different kinds of logger
 */
class FileLoggerFactory implements LoggerFactory
{
    public function __construct(private string $filePath)
    {
    }

    public function createLogger(): Logger
    {
        return new FileLogger($this->filePath);
    }
}

class StdoutLoggerFactory implements LoggerFactory
{
    public function createLogger(): Logger
    {
        return new StdoutLogger();
    }
}


/**
 * Logger interface declares behaviors of various types of logger
 */
interface Logger
{
    public function log(string $message);
}


class FileLogger implements Logger
{
    public function __construct(private string $filePath)
    {
    }

    public function log(string $message)
    {
        file_put_contents($this->filePath, $message . PHP_EOL, FILE_APPEND);
    }
}

class StdoutLogger implements Logger
{
    public function log(string $message)
    {
        echo $message;
    }
}

# Client code example
$message = 'Hey there!';
$loggerType = 'file'; // taken from configuration for example
$filePath = 'path/to/logfile';

switch ($loggerType) {
    case 'file':
        $loggerFactory = new FileLoggerFactory($filePath);
        break;
    case 'stdout':
        $loggerFactory = new StdoutLoggerFactory();
        break;
}

$loggerFactory->createLogger()->log($message);
