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


## Anonymous Classes

An anonymous class is a class that is defined and instantiated at the same time, without being assigned a specific name, useful for creating simple, one-off objects where a full class definition in a separate file would be unnecessary or add overhead.

- **Use Cases:**
    - **Testing and Mocking:** Creating simple mock objects for unit tests without needing to define a separate class file.
    - **Callbacks and Event Handlers:** Providing quick, inline implementations for interfaces or abstract classes required by a callback or event listener.
    - **Simple, Temporary Objects:** When a small, self-contained object is needed for a very specific, local purpose and will not be reused elsewhere.

```php
interface LoggerInterface
{
    public function log(string $message);
}

// Using an anonymous class to implement LoggerInterface
$logger = new class implements LoggerInterface {
    public function log(string $message)
    {
        echo "LOG: " . $message . PHP_EOL;
    }
};

$logger->log("This is a test message.");

// Anonymous class extending another class
class BaseClass
{
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

$extendedObject = new class('Anonymous Extension') extends BaseClass {
    public function greet(): string
    {
        return "Hello from " . $this->getName();
    }
};

echo $extendedObject->greet() . PHP_EOL;
```


## Object Comparison 

In PHP, objects can be compared using two main operators: the comparison operator (`==`) and the identity operator (`===`).


### Comparison Operator (`==`)

When using the `==` operator to compare two objects, PHP determines if the objects are "equal" based on the following criteria:

- **Same Class:** Both objects must be instances of the same class.
- **Same Attributes and Values:** Both objects must have the same attributes (properties) with the same values. The values themselves are compared using `==`.

```php
class MyClass {
    public $prop1;
    public $prop2;

    public function __construct($p1, $p2) {
        $this->prop1 = $p1;
        $this->prop2 = $p2;
    }
}

$obj1 = new MyClass("valueA", 123);
$obj2 = new MyClass("valueA", 123);
$obj3 = new MyClass("valueB", 456);

if ($obj1 == $obj2) {
    echo "obj1 and obj2 are equal (==)\n"; // This will be true
}

if ($obj1 == $obj3) {
    echo "obj1 and obj3 are equal (==)\n"; // This will be false
}
```

### Identity Operator (`===`)

The `===` operator performs a stricter comparison, checking for "identity." Two object variables are considered identical if: 

- **Same Instance:** They refer to the exact same instance of the same class in memory. This means they are essentially two different names pointing to the same object.

```php
class MyClass {
    public $prop1;

    public function __construct($p1) {
        $this->prop1 = $p1;
    }
}

$objA = new MyClass("data");
$objB = new MyClass("data");
$objC = $objA; // $objC now refers to the same instance as $objA

if ($objA === $objB) {
    echo "objA and objB are identical (===)\n"; // This will be false
}

if ($objA === $objC) {
    echo "objA and objC are identical (===)\n"; // This will be true
}
```


## Covariance, Contravariance, Invariance

Covariance, contravariance, and invariance refer to how type declarations for parameters and return types behave when overriding methods in child classes or implementing interfaces. These concepts were fully supported starting from PHP 7.4.

- **Covariance:** "Narrows" the return type (more specific).
- **Contravariance:** "Widens" the parameter type (less specific).
- **Invariance:** Requires an exact match of the type.

These concepts are crucial for maintaining type safety and flexibility in object-oriented programming, particularly when dealing with **polymorphism** and **inheritance hierarchies.**
### Covariance

Covariance allows a child method's return type to be a more specific type than the return type of its parent's method or the interface's method. 
This means if a parent method returns a `Fruit` object, the child method can return an `Apple` object (assuming `Apple` is a subclass of `Fruit`).

```php
class Fruit {}
class Apple extends Fruit {}

class Basket
{
    public function getFruit(): Fruit
    {
        return new Fruit();
    }
}

class AppleBasket extends Basket
{
    // Covariant return type: Apple is a more specific type than Fruit
    public function getFruit(): Apple
    {
        return new Apple();
    }
}
```
