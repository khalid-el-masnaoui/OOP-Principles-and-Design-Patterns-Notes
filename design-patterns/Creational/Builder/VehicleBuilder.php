<?php

namespace DesignPatterns\Creational;
 
 /**
 * Vehicle Builder Abstraction
 */
interface Builder
{
    public function createVehicle(): void;

    public function addWheel(): void;

    public function addEngine(): void;

    public function addDoors(): void;

    public function getVehicle(): Vehicle;
}


/**
 * Vehicle Builder Implementations
 */

class CarBuilder implements Builder
{
    private Car $car;

    public function addDoors(): void
    {
        $this->car->setPart('rightDoor', new Door());
        $this->car->setPart('leftDoor', new Door());
        $this->car->setPart('trunkLid', new Door());
    }

    public function addEngine(): void
    {
        $this->car->setPart('engine', new Engine());
    }

    public function addWheel(): void
    {
        $this->car->setPart('wheelLF', new Wheel());
        $this->car->setPart('wheelRF', new Wheel());
        $this->car->setPart('wheelLR', new Wheel());
        $this->car->setPart('wheelRR', new Wheel());
    }

    public function createVehicle(): void
    {
        $this->car = new Car();
    }

    public function getVehicle(): Vehicle
    {
        return $this->car;
    }
}

class TruckBuilder implements Builder
{
    private Truck $truck;

    public function addDoors(): void
    {
        $this->truck->setPart('rightDoor', new Door());
        $this->truck->setPart('leftDoor', new Door());
    }

    public function addEngine(): void
    {
        $this->truck->setPart('truckEngine', new Engine());
    }

    public function addWheel(): void
    {
        $this->truck->setPart('wheel1', new Wheel());
        $this->truck->setPart('wheel2', new Wheel());
        $this->truck->setPart('wheel3', new Wheel());
        $this->truck->setPart('wheel4', new Wheel());
        $this->truck->setPart('wheel5', new Wheel());
        $this->truck->setPart('wheel6', new Wheel());
    }

    public function createVehicle(): void
    {
        $this->truck = new Truck();
    }

    public function getVehicle(): Vehicle
    {
        return $this->truck;
    }
}

/**
 * Director Class
 */
class Director
{
    public function build(Builder $builder): Vehicle
    {
        $builder->createVehicle();
        $builder->addDoors();
        $builder->addEngine();
        $builder->addWheel();

        return $builder->getVehicle();
    }
}


/**
* Vehicle Abstraction
*/
abstract class Vehicle
{
    final public function setPart(string $key, object $value)
    {
    	//..code
    }
}

/**
* Vehicle IMplementations
*/
class Car extends Vehicle
{
}



class Truck extends Vehicle
{
}

/**
* Vehcile Parts Classes
*/
class Wheel
{
}

class Door
{
}

class Engine
{
}


# Client code example
$director = new Director();
$truck = $director->build(new TruckBuilder());
