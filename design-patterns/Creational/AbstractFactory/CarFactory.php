<?php

namespace DesignPatterns\Creational;

/**
 * Abstract Factory defines an interface for creating all distinct products,
 * but leaves the actual product creation to concrete factory classes
 */
abstract class CarFactory
{
    abstract public function build();
}

/**
 * Each factory type corresponds to a certain product variety
 */
class RedCarFactory extends CarFactory
{
    private $options;

    public function __construct($doors)
    {
        $options = new CarOptions();
        $options->color = "Red";
        $options->doors = $doors;

        $this->options = $options;
    }

    public function build()
    {
        return new Car($this->options);
    }
}

class BlueCarFactory extends CarFactory
{
    private $options;

    public function __construct($doors)
    {
        $options = new CarOptions();
        $options->color = "Blue";
        $options->doors = $doors;

        $this->options = $options;
    }

    public function build()
    {
        return new Car($this->options);
    }
}
 
 
class Car
{
    private $color;
    private $doors;

    public function __construct(CarOptions $options)
    {
        $this->color = $options->color;
        $this->doors = $options->doors;
    }

    public function info()
    {
        return "This is a $this->doors doors $this->color car.\n";
    }
}

class CarOptions
{
    var $color = "White";
    var $doors = 4;
}

# Client code example
// the factory is selected based on the environment/configuration parameters
$carColor = 'blue';
switch ($carColor) {
    case 'blue':
        $carFactory = new BlueCarFactory(4);
        break;
    case 'red':
        $carFactory = new RedCarFactory(4);
        break;
}

// we will have header and body as either Smarty or Blade template, but never mixed
echo $carFactory->build()->info();
/* Output: This is a 5 doors Blue car. */

