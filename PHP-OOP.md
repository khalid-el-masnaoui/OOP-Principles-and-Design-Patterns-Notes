# PHP OOP

## Overview 

Object-Oriented Programming (OOP) in PHP is a programming paradigm that organizes code around objects rather than functions and logic. Exploring some PHP specific OOP implementations.



# PHP Specific OOP implementations
## Classes & Objects

- **Classes:** A blueprint or template for creating objects. It defines properties (data/attributes) and methods (functions/behaviors) that objects of that class will possess.

- **Objects:** An instance of a class. When a class is defined, no memory is allocated until an object of that class is created.


```php 
class Car {
    // Properties
    public $brand;
    public $model;
    public $year;

    // Methods
    public function startEngine() {
        echo "The " . $this->brand . " " . $this->model . " engine is starting.<br>";
    }

    public function getDetails() {
        return $this->brand . " " . $this->model . " (" . $this->year . ")";
    }
}

// Creating an object (instantiation)
$myCar = new Car();

// Setting object properties
$myCar->brand = "Toyota";
$myCar->model = "Camry";
$myCar->year = 2023;

// Accessing object methods
$myCar->startEngine(); // Output: The Toyota Camry engine is starting.
echo "Car details: " . $myCar->getDetails() . "<br>"; // Output: Car details: Toyota Camry (2023)

// Creating another object
$anotherCar = new Car();
$anotherCar->brand = "Honda";
$anotherCar->model = "Civic";
$anotherCar->year = 2022;

$anotherCar->startEngine(); // Output: The Honda Civic engine is starting.
echo "Another car details: " . $anotherCar->getDetails() . "<br>"; // Output: Another car details: Honda Civic (2022)
```

## Namespaces

Namespaces prevent name conflicts when you use multiple classes with the same name.

```php
namespace App\Models;

class User {
    public function getInfo() {
        echo "the info for the user";
    }
}

 
$user = new \App\Models\User();
$user->getInfo();
```
