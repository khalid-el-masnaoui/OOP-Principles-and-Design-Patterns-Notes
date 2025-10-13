<?php

namespace DesignPatterns\Creational;

/**
 * Abstract Factory defines an interface for creating all distinct products,
 * but leaves the actual product creation to concrete factory classes
 */
interface WriterFactory
{
    public function createCsvWriter(): CsvWriter;
    public function createJsonWriter(): JsonWriter;
}

/**
 * Each factory type corresponds to a certain product variety
 */
 class UnixWriterFactory implements WriterFactory
{
    public function createCsvWriter(): CsvWriter
    {
        return new UnixCsvWriter();
    }

    public function createJsonWriter(): JsonWriter
    {
        return new UnixJsonWriter();
    }
}

class WinWriterFactory implements WriterFactory
{
    public function createCsvWriter(): CsvWriter
    {
        return new WinCsvWriter();
    }

    public function createJsonWriter(): JsonWriter
    {
        return new WinJsonWriter();
    }
}

/**
 * The base interface for csvWriter (products) family
 */
 interface CsvWriter
{
    public function write(array $line): string;
}

class UnixCsvWriter implements CsvWriter
{
    public function write(array $line): string
    {
        return join(',', $line) . "\n";
    }
}

class WinCsvWriter implements CsvWriter
{
    public function write(array $line): string
    {
        return join(',', $line) . "\r\n";
    }
}


/**
 * Another products family
 */
interface JsonWriter
{
    public function write(array $data, bool $formatted): string;
}


class UnixJsonWriter implements JsonWriter
{
    public function write(array $data, bool $formatted): string
    {
        $options = 0;

        if ($formatted) {
            $options = JSON_PRETTY_PRINT;
        }

        return json_encode($data, $options);
    }
}

class WinJsonWriter implements JsonWriter
{
    public function write(array $data, bool $formatted): string
    {
        $options = 0;

        if ($formatted) {
            $options = JSON_PRETTY_PRINT;
        }

        return json_encode($data, $options);
    }
}

# Client code example
// the factory is selected based on the environment/configuration parameters
$writerType = 'unix';
switch ($writerType) {
    case 'unix':
        $writerFactory = new UnixWriterFactory();
        break;
    case 'win':
        $writerFactory = new WinWriterFactory();
        break;
}

// we will have csv and json written as in either Unix or Win format, but never mixed
echo $writerFactory->createCsvWriter()->write(array('item1', 'item2', 'item3'));
echo $writerFactory->createJsonWriter()->write(array('item1' => 'chair', 'item2' => 'table', 'item3' => 'sofa'));
/* Output: 
 item1,item2,item3
 {
    'item1' : 'chair',
    'item2' : 'table',
    'item3' : 'sofa'
 }
 */




