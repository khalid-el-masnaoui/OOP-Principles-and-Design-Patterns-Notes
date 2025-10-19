<?php

namespace DesignPatterns\Creational;

interface Vehicle
{
    public function drive(): string;
}

class Car implements Vehicle
{
    private string $model;

    public function __construct(string $model)
    {
        $this->model = $model;
    }

    public function drive(): string
    {
        return "Driving a " . $this->model . " car.";
    }
}

class Bicycle implements Vehicle
{
    public function drive(): string
    {
        return "Riding a bicycle.";
    }
}

class VehicleFactory
{
    public static function createVehicle(string $type, ?string $model = null): Vehicle
    {
        switch ($type) {
            case 'car':
                if ($model === null) {
                    throw new InvalidArgumentException("Car requires a model.");
                }
                return new Car($model);
            case 'bicycle':
                return new Bicycle();
            default:
                throw new InvalidArgumentException("Unknown vehicle type: $type");
        }
    }
}

// Client code
$myCar = VehicleFactory::createVehicle('car', 'Tesla Model S');
echo $myCar->drive() . "\n"; // Output: Driving a Tesla Model S car.

$myBicycle = VehicleFactory::createVehicle('bicycle');
echo $myBicycle->drive() . "\n"; // Output: Riding a bicycle.

try {
    VehicleFactory::createVehicle('car');
} catch (InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "\n"; // Output: Error: Car requires a model.
}
?>
