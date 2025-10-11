# OOP Principles and Design Patterns

## Overview

Object-Oriented Programming (OOP) is a programming paradigm that structures code around "objects" rather than functions and logic. This approach aims to model real-world entities and their interactions within a program.


# Fundamental Principles of Object-Oriented Programming


## Encapsulation

> Encapsulation is bundling data (properties) and the methods that operate on that data within a single unit (the class). This protects data integrity and controls access to internal states using access modifiers (public, protected, private).

Encapsulation is one of the fundamental principles of OOP. It is crucial because it enables a high level of data integrity by shielding an object's internal state from unwanted interference and misuse.


### Implementation

To implement encapsulation in PHP, we use **access modifiers**: `public`, `protected`, and `private` when declaring class properties and methods. 

Implementing **getter** and **setter** methods, which are public methods that allow controlled access to private or protected properties, further enforcing encapsulation by letting the class control the values and operations allowed on its properties. 
The data might need further manipulation : 

```php
class Date {
	protected \DateTime $date;
	//...
	
	// Date and time is presented as a string
	public function setDate(string $date)
	{
		// You wish the property to be stored as a DateTime instance
		$this→date = new DateTime($date);
	}

}
```


### Key Benefits

- Encapsulation brings numerous benefits to applications. By controlling how the data within objects is accessed and modified, encapsulation promotes a more modular and cohesive codebase. 
- Updates and bug fixes to a class can often be made with minimal impact on the rest of the application, as long as the public interface of the class remains unchanged. This makes applications easier to maintain and extend over time.
- Encapsulation also enhances security, as critical parts of the code are hidden from external access, reducing the risk of accidental or malicious tampering. 
- Encapsulated code is more likely to be reusable, since the implementation details are separated from the exposed interface, allowing us to leverage the same classes across different parts of the application or in entirely different projects without risking unwanted side effects.



## Abstraction

> Abstraction is hiding complex implementation details and showing only essential information to the user. This is achieved through `abstract` classes and `interfaces`.


### Abstract Classes

Abstract classes are declared with the `abstract` keyword and are used to define methods that must be implemented by any subclass that extends the abstract class. (an abstract class cannot be instantiated on its own)

- **Declaration:** An abstract class is defined by prefixing the class name with the `abstract` keyword.
- **Can contain abstract methods :** These are methods declared with the `abstract` keyword and only define the method signature (name, parameters, and visibility <`public`, `protected`>) without providing an implementation. Any class extending an abstract class must implement all of its abstract methods.
- **Can contain concrete methods,  properties and constants** : should be <`public`, `protected`>

```php
abstract class Animal {
    protected string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }

    // Abstract method - must be implemented by child classes
    abstract public function makeSound();

    // Concrete method - inherited by child classes
    public function eat() {
        echo $this->name . " is eating.\n";
    }
}

```

- **Extending:** To utilize an abstract class, a subclass must extend it and provide implementations for the abstract methods.

```php
class Dog extends Animal {
    public function makeSound() {
        echo $this->name . " says Woof!\n";
    }
}

class Cat extends Animal {
    public function makeSound() {
        echo $this->name . " says Meow!\n";
    }
}
```

- **Instantiation**: You cannot create an instance of an abstract class directly.

```php
$dog = new Dog("Buddy");
$dog->makeSound(); // Output: Buddy says Woof!
$dog->eat();      // Output: Buddy is eating.

$cat = new Cat("Whiskers");
$cat->makeSound(); // Output: Whiskers says Meow!
$cat->eat();      // Output: Whiskers is eating.

// This would cause an error: Cannot instantiate abstract class Animal
// $animal = new Animal("Generic Animal");
```

- **Purpose:** The primary purpose of an abstract class is to provide a common definition of a base class that multiple derived classes can share. The focus of an abstract class is on building an **inheritance hierarchy of classes**.


### Interfaces

Interfaces provide a blueprint for classes, defining a contract that classes must adhere to. They specify a set of methods that a class must implement, but without providing any implementation details for those methods.


- **Contractual Agreement:** An interface acts as a contract, ensuring that any class implementing it will possess the specified methods. This promotes consistency and predictable behavior across different classes that share a common functionality.

- **Method Declarations Only:** Interfaces contain only method declarations (signatures), not their actual code or logic. The implementation of these methods is left to the concrete classes that implement the interface.

- **No Properties (Variables):** Interfaces cannot contain properties (variables), only constants.

- **Public Methods:** All methods declared within an interface must be declared as `public`. 

- `implements` Keyword: Classes use the `implements` keyword to declare that they will adhere to the contract defined by an interface. A class can implement multiple interfaces, separating them with commas.

- **Polymorphism:** Interfaces are crucial for achieving polymorphism in PHP. They allow different classes to be treated uniformly through a common interface, even if their underlying implementations differ. This enables greater flexibility and extensibility in code.

- **Extending Interfaces:** Interfaces can extend other interfaces using the `extends` keyword. A class implementing an extended interface must implement methods from all interfaces in the inheritance chain.



```php 
interface Cache
{
    public function read(string $key): ?string;
    public function write(string $key, string $value, int $ttl = 0): bool;
}

class RedisCache implements Cache
{
    public function read(string $key): ?string
    {
        // Redis-specific read logic
        return "Data from Redis for " . $key;
    }

    public function write(string $key, string $value, int $ttl = 0): bool
    {
        // Redis-specific write logic
        return true;
    }
}

class FileCache implements Cache
{
    public function read(string $key): ?string
    {
        // File-specific read logic
        return "Data from File for " . $key;
    }

    public function write(string $key, string $value, int $ttl = 0): bool
    {
        // File-specific write logic
        return true;
    }
}

function processData(Cache $cache, string $key, string $value = null)
{
    if ($value) {
        $cache->write($key, $value);
        echo "Wrote '{$value}' to {$key} using " . get_class($cache) . "<br>";
    } else {
        $data = $cache->read($key);
        echo "Read '{$data}' for {$key} using " . get_class($cache) . "<br>";
    }
}

$redis = new RedisCache();
$file = new FileCache();

processData($redis, "user:123", "khalid el");
processData($file, "product:456", "Widget A");
processData($redis, "user:123");
processData($file, "product:456");
```

Interfaces can technically declare a `__construct()` method, but it is strongly discouraged and generally considered a bad practice.



## Inheritance

> Inheritance allows a new class (child class) to inherit properties and methods from an existing class (parent class). This promotes code reusability and establishes a "is-a" relationship between classes.


This mechanism enables code reusability and establishes a relationship between classes where the subclass is a specialized version of the superclass. The subclass can add its own unique features or override the behavior of the superclass, which promotes a hierarchical organization of classes.


- **Code Reusability:**  Child classes automatically inherit the public and protected properties and methods of their parent class, eliminating the need to rewrite common code.
    
- `extends` Keyword:  The `extends` keyword is used to establish the inheritance relationship, where a child class extends a parent class.

- **Visibility:**
    - **Public & protected**:  properties and methods are inherited by child classes.
    - **Private**: properties and methods are not inherited and remain exclusive to their defining class.
    
- **Overriding:**  Child classes can override inherited public and protected methods and properties to provide their own specific implementations.
    - When overriding methods, the child method's signature must be compatible with the parent method's signature.
    - When overriding properties, the visibility of the child property cannot be decreased (e.g., a public parent property cannot be overridden as protected in the child).
    
- **Constructors:**  When a child class defines its own constructor and overrides the parent's constructor, it does not automatically call the parent's constructor. The parent's constructor needs to be explicitly called using `parent::__construct()` within the child's constructor if its logic is still required.
    
- `parent::` Keyword: This keyword allows access to the parent class's methods or constants, particularly useful when overriding methods but still needing to execute the parent's logic.

```php 
class Animal {
    public $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function eat() {
        echo $this->name . " is eating.\n";
    }

    public function sleep() {
        echo $this->name . " is sleeping.\n";
    }
}

// Child class inheriting from Animal
class Dog extends Animal {
    public function bark() {
        echo $this->name . " is barking: Woof! Woof!\n";
    }

    // Overriding the sleep method from the parent class
    public function sleep() {
        echo $this->name . " is sleeping soundly in its bed.\n";
    }
}

// Child class inheriting from Animal
class Cat extends Animal {
    public function meow() {
        echo $this->name . " is meowing: Meow!\n";
    }
}
```


#### Best Practices

- **Use Inheritance Sparingly:** Inheritance should only be used when there is a clear relationship and shared behavior. Overusing inheritance can lead to complex hierarchies that are hard to understand and maintain.

- **Prefer Composition Over Inheritance:** In many cases, using composition (including objects of other classes as properties) can be more flexible than inheritance. This is often referred to as the `has a` relationship.

- **Protected Over Private:** If you think a property or method should be hidden from the public but still accessible to child classes, use protected instead of private visibility. This ensures encapsulation while still allowing inheritance.

- **Liskov Substitution Principle (LSP):** When overriding methods, make sure that the child class's methods can safely replace the parent class's methods. This means they should accept the same input parameters and return the same types.

- **Avoid Deep Inheritance Hierarchies:** Deeply nested inheritance can become problematic and hard to follow. Instead, aim for a shallow hierarchy and consider other design patterns, like interfaces, to provide flexibility.
