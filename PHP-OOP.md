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


## Traits

Traits allow code reuse in multiple classes without using inheritance.

```php
trait Logger {
	public function log($message) {
		echo "Logging: " . $message;
	}
}

class User {
	use Logger;
	public function createUser() {
		$this->log('User created.');
	}
}

$user = new User();
$user->createUser();
```


## Magic Methods

PHP provides several **magic methods** which add dynamic behaviors to objects.  They are special methods that are called automatically when certain conditions are met.  Every magic method starts with a double underscore (  __ ).


- `__construct` :  This method executes  when an object is created
- `__destruct` : This method executes  when an object is no longer needed.
- `__call($name,$parameter)`: This method executes when a method is called which is not non-existent or inaccessible.
- `__callStatic($name,$parameter)` : This method executes when a **static**  method is called which is not non-existent or inaccessible.
- `__toString` : This method is called when we need to convert the object into a string.
- `__get($name)` : This method is called when an inaccessible variable or non-existing variable is used.
- `__set($name , $value)` : This method is called when an inaccessible variable or non-existing variable is written.
- `__isset($name)` : Invoked when the `isset()` or `empty()` functions are used on an inaccessible or non-existent property of an object.
- `__unset($name)` : Invoked when the `unset()` function is used on an inaccessible or non-existent property of an object.
- `__invoke(...$name)` : Allows an object to be called as if it were a function.
- `__clone()` : Invoked whenever an object is cloned using the `clone` keyword. Its purpose :
	- **Deep Copying:** (object properties)
	- **Reassigning Properties**
	- **Cleanup or Initialization**


```php
class Address {
	private string $street;
	public function __construct(string $street) {
		$this->street = $street;
	}
}

class User {

	private string $firstName;
    private string $lastName;
    
    private Address $address;


    public function __construct(string $firstName, string $lastName, Address $address) {
	    $this->firstName = $firstName;  
		$this->lastName = $lastName;
		$this->address = $address;
        echo "User object created";
    }

    public function __destruct() {
        echo "User object deleted";
    }
    
    public function __call(string $name, array $arguments) {
        echo "Attempted to call method: '{$name}'\n";
        echo "Arguments passed: " . implode(', ', $arguments) . "\n";

        if ($name === 'greet') {
            return "Hello, " . $arguments[0] . "!";
        } else {
            throw new BadMethodCallException("Method '{$name}' does not exist.");
        }
    }
    
      public static function __callStatic(string $method, array $arguments) {
        echo "Attempted to call static method '{$method}' with arguments: ";
        print_r($arguments);
        // You can implement custom logic here, like redirecting to another method,
        // dynamically creating the method, or throwing an exception.
    }
    
    public function __toString(): string{
        return "User: {$this->firstName} {$this->lastName}";
    }
    
    public function __get(string $name): mixed {
	    if ($name === 'fullName') {
            return "{$this->firstName} {$this->lastName}";
        }
        // Handle non-existent/inaccessible property
        trigger_error("Attempt to access non-existent or inaccessible property: $name", E_USER_WARNING);
        return null;
    }
    
     public function __set(string $name, mixed $value): void {
        // Example: Implementing a property alias
        if ($name === 'fullName') {
	        $fullName = explode(' ', $name, 2);
            $this->firstName = $fullName[0];
            $this->lastName = $fullName[1] ?? '';
        }
    }
    
    public function __isset($name){
        return isset($name);
    }
    
   public function __unset($name) {
		echo "Attempting to unset '$name'\n";
		unset($this->$name); // Unset the property from the internal array
	}
	
	public function __invoke($name)
    {
        return "Hello, " . $name . "!";
    }
    
    public function __clone() {
		// Deep copy the Address object
		$this->address = clone $this->address;
    }
}
$address = new Address("123 Main St");
$user = new User("khalid", "malidkha", $address); // Output => User object created

// This will trigger __call()  
echo $user->greet('World') . "\n";  
  
// This will also trigger __call() and then the custom exception  
try {  
	$user->unknownMethod('param1', 'param2');  
} catch (BadMethodCallException $e) {  
	echo $e->getMessage() . "\n";  
}


user::nonExistentMethod('value1', 123);
// Output => Attempted to call static method 'nonExistentMethod' with arguments: Array  (  [0] => value1  [1] => 123  )

echo $user; // Output => User: khalid malidkha

echo $user->fullName; // Output => khalid malidkha
echo $user->city; // Triggers a warning and outputs: null

$user->fullName = 'malidkha el'; // Calls __set
  
echo $user->fullName; // Outputs => malidkha el  

var_dump(isset($user->firstName)); // Calls __isset, returns true
var_dump(isset($user->fullName)); // Calls __isset, returns false

try {
	unset($user->email); // Throws an exception
} catch (Exception $e) {
	echo $e->getMessage();
}

echo $greet("malidkha"); // Output => Hello, malidkha!

$clonedUser = clone $user;
// Modifying the cloned user's address will not affect the original
$clonedUser->address->street = "456 Oak Ave";

echo $user->address->street; // Output => 123 Main St
echo $clonedUser->address->street;   // Output => 456 Oak Ave

```


## `$this`, `self`, and `static` keywords

### `$this`

- Refers to the **current instance** of the class.
- Used to access non-static properties and methods of that specific object.
- Cannot be used within static methods as static methods are not tied to a specific object instance.


### `self`

- Refers to the **current class** itself, not a specific object instance.
- Used to access static properties and methods within the same class. 
- Accessed using the scope resolution operator (`::`), for example, `self::$staticProperty` or `self::staticMethod()`.
- Does not account for inheritance and will always refer to the class in which it is written, even if a child class overrides the static member.

### `static`

- Also refers to the **current class**, similar to `self`, but with a key difference in inheritance.
- Used for **late static binding**, meaning it refers to the class that was called at **runtime**, not necessarily the class where the `static` keyword is written.
- Allows for **polymorphic** behavior with static members, where a child class's overridden static property or method will be used when called through the child class.
- Accessed using the scope resolution operator (`::`), for example, `static::$staticProperty` or `static::staticMethod()`.

### Summary

- Use `$this` for **instance-level** (non-static) members.
- Use `self` for static members when you want to explicitly refer to the current class and ignore potential overrides in child classes.
- Use `static` for static members when you want to leverage late static binding and allow for polymorphic behavior in child classes.


## Overloading

Overloading refers to the ability to dynamically create properties and methods in a class, which are then handled by **magic methods**.

- **Property Overloading:**
    - `__set($name, $value)`
    - `__get($name)`
    - `__isset($name)`
    - `__unset($name)`

- **Method Overloading:**
    - `__call($name, $arguments)`
    - `__callStatic($name, $arguments)`

