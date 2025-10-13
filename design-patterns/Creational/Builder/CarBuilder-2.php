<?php

namespace DesignPatterns\Creational;

/**
 * The main idea behind Builder pattern is prevent "telescoping constructor"
 * public function __construct($name, $value, $param1 = true, $param2 = true, $param3 = false, ..) {}
 */
class Car
{
    public $front;
    public $door;
    public $rear;

    public function show()
    {
        echo "The color of the front: {$this->front}".PHP_EOL;
        echo "The color of the door: {$this->door}".PHP_EOL;
        echo "The color of the rear: {$this->rear}".PHP_EOL;
    }
}

/**
 * car Builder Abstraction
 */
abstract class CarBuilder
{
    protected Car $car;

    public function __construct()
    {
        $this->car = new Car();
    }

    abstract public function BuildFront();

    abstract public function BuildDoor();

    abstract public function BuildRear();

    abstract public function GetCar();
}

/**
 * Car Builder Implementations
 */
class BlueCar extends CarBuilder
{
    public function BuildFront()
    {
        $this->car->front = 'blue';
    }

    public function BuildDoor()
    {
        $this->car->door = 'blue';
    }

    public function BuildRear()
    {
        $this->car->rear = 'blue';
    }

    public function GetCar()
    {
        return $this->car;
    }
}

class BlueCar extends CarBuilder
{
    public function BuildFront()
    {
        $this->car->front = 'red';
    }

    public function BuildDoor()
    {
        $this->car->door = 'red';
    }

    public function BuildRear()
    {
        $this->car->rear = 'red';
    }

    public function GetCar()
    {
        return $this->car;
    }
}

/**
 * Director Class
 */
class Director
{
    public function Construct(CarBuilder $builder)
    {
        $builder->BuildFront();
        $builder->BuildDoor();
        $builder->BuildRear();

        return $builder->GetCar();
    }
}

# Client code example
$director = new Director();
$blueCar = $director->Construct(new BlueCar());
$blueCar->Show();




