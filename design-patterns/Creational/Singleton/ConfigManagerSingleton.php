<?php

namespace DesignPatterns\Creational;

class Config
{
    private static ?Config $instance = null;
    private array $settings = [];

    // Private constructor to prevent direct instantiation
    private function __construct()
    {
        // Load configuration from a file or other source
        // For this example, we'll use a simple array
        $this->settings = [
            'database' => [
                'host' => 'localhost',
                'username' => 'root',
                'password' => 'password',
                'dbname' => 'mydb'
            ],
            'app' => [
                'name' => 'My Application',
                'debug_mode' => true
            ]
        ];
    }

    // Prevent cloning of the instance
    private function __clone() {}

    // Prevent unserialization of the instance
    private function __wakeup() {}

    /**
     * Get the single instance of the Config class.
     *
     * @return Config
     */
    public static function getInstance(): Config
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get a configuration setting by key.
     *
     * @param string $key The key of the setting to retrieve (e.g., 'database.host')
     * @param mixed $default The default value to return if the key is not found
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $parts = explode('.', $key);
        $current = $this->settings;

        foreach ($parts as $part) {
            if (is_array($current) && array_key_exists($part, $current)) {
                $current = $current[$part];
            } else {
                return $default;
            }
        }
        return $current;
    }

    /**
     * Set a configuration setting.
     *
     * @param string $key The key of the setting to set (e.g., 'app.debug_mode')
     * @param mixed $value The value to set
     */
    public function set(string $key, mixed $value): void
    {
        $parts = explode('.', $key);
        $current = &$this->settings; // Use reference to modify the original array

        foreach ($parts as $index => $part) {
            if ($index === count($parts) - 1) {
                $current[$part] = $value;
            } else {
                if (!isset($current[$part]) || !is_array($current[$part])) {
                    $current[$part] = [];
                }
                $current = &$current[$part];
            }
        }
    }
}

// Usage example:
$config = Config::getInstance();

echo "Database Host: " . $config->get('database.host') . "\n";
echo "Application Name: " . $config->get('app.name') . "\n";

$config->set('app.debug_mode', false);
echo "Debug Mode: " . ($config->get('app.debug_mode') ? 'true' : 'false') . "\n";

// Attempting to create a new instance directly will result in an error
// $newConfig = new Config(); // Fatal error: Call to private constructor

// Getting the instance again returns the same object
$anotherConfig = Config::getInstance();
echo "Is it the same instance? " . ($config === $anotherConfig ? 'Yes' : 'No') . "\n";

?>
