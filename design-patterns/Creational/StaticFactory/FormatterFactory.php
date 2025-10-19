<?php

namespace DesignPatterns\Creational;

interface Formatter
{
    public function format(mixed $data): string;
}

class NumberFormatter implements Formatter
{
    public function format(mixed $data): string
    {
        return number_format((float)$data, 2);
    }
}

class StringFormatter implements Formatter
{
    public function format(mixed $data): string
    {
        return strtoupper((string)$data);
    }
}

class FormatterFactory
{
    public static function createFormatter(string $type): Formatter
    {
        switch ($type) {
            case 'number':
                return new NumberFormatter();
            case 'string':
                return new StringFormatter();
            default:
                throw new InvalidArgumentException("Unknown formatter type: $type");
        }
    }
}

// Client code
$numberFormatter = FormatterFactory::createFormatter('number');
echo $numberFormatter->format(1234.5678) . "\n"; // Output: 1,234.57

$stringFormatter = FormatterFactory::createFormatter('string');
echo $stringFormatter->format("hello world") . "\n"; // Output: HELLO WORLD

try {
    FormatterFactory::createFormatter('invalid');
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "\n"; // Output: Error: Unknown formatter type: invalid
}
?>
