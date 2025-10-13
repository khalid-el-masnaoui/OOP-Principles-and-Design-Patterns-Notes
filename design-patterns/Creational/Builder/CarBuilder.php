<?php

namespace DesignPatterns\Creational;

// === CarBuilder that create Car object for us ===
// But instead of providing all properties on creation
// we can set them one-by-one through setter methods
class CarBuilder
{
    private $color;
    private $doors;

    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    public function setDoors($doors)
    {
        $this->doors = $doors;
        return $this;
    }

    public function getColor()
    {
        return $this->color;
    }

    public function getDoors()
    {
        return $this->doors;
    }

    function build()
    {
        return new Car($this);
    }
}

/**
 * Director Class
 */
class Director
{
    public function buildSprotsCar(carBuilder $carBuilder): Car
    {
        return $carBuilder->setColor("Red")->setDoors(2)->build();
    }
    
    public function buildSUVCar(carBuilder $carBuilder): Car
    {
        return $carBuilder->setColor("Silver")->setDoors(5)->build();
    }
}


class Car
{
    public $color;
    public $doors;

    public function __construct(CarBuilder $carBuilder)
    {
        $this->color = $carBuilder->getColor();
        $this->doors = $carBuilder->getDoors();
    }

    static function builder()
    {
        return new CarBuilder();
    }
}

# Client code example
$car = Car::builder()->setColor("Silver")->setDoors(5)->build();

print_r($car);
// Output: Car Object([color] => Silver, [doors] => 5)

$director = new Director();
$sportCar = $director->buildSprotsCar(new CarBuilder());


