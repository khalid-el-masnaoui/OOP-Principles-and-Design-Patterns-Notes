# OOP Design Principles

## Overview

The three goals of Object Oriented Programming are **Robustness**, **Adaptability**, and **Reusability**. OOP design principles are guidelines intended for programmers to apply while working on software leading to achieving the previous mentioned goals of OOP.



# SOLID Principles

**SOLID** principles are five fundamental object-oriented design guidelines
- `Single Responsibility`
- `Open-Closed`
- `Liskov Substitution`
- `Interface Segregation`
- `Dependency Inversion`
These principles establish practices for developing software with considerations for maintaining and extending it as the project grows. Adopting these practices can also help avoid code smells, refactor code, and develop Agile or Adaptive software.

## Single Responsibility Principle (SRP)

> A class should have one, and only one, reason to change.

A class that manages several tasks increases coupling and complicates maintenance. Rather, divide issues into distinct classes.

Why **SRP** is Important
- Simplifies debugging and testing.
- Enhances reusability of code.
- Reduces unintended side effects when modifying code.

#### Violation

1. This interface has two overlapped responsibilities coexisting here: get data and format it.

```php
interface Report
{
    public function getTitle();
    public function getDate();
    public function getHeaders();
    public function getBody();
    public function toCSV();
    public function toHTML($name);
}
```

2. Here, `UserManager` class is responsible for both user persistence and email notifications, which are unrelated concerns.

```php
class UserManager {
    public function saveUser($user) {
        // Code to save user to database
    }

    public function sendEmail($user) {
        // Code to send an email
    }
}
```

#### Remedy

1. The issue could be solved by using two interfaces: `Report` and `ReportFormatter`.

```php
interface Report
{
    public function getTitle();
    public function getDate();
    public function getHeaders();
    public function getBody();
}

interface ReportFormatter
{
    public function format(Report $report);
}

class HtmlReportFormatter implements ReportFormatter
{
    public function format(Report $report)
    {
        $output = '';
        // ...

        return $output;
    }
}
```

2. `EmailService` now manages email alerts, whereas `UserRepository` simply manages database interactions. Every class is responsible for one thing.

```php
class UserRepository {
    public function saveUser($user) {
        // Code to save user to database
    }
}

class EmailService {
    public function sendEmail($user) {
        // Code to send an email
    }
}
```


## Open Closed Principle (OCP)

> You should be able to extend a classes behavior, without modifying it.

**This principle is about class design and feature extensions**, it based on delegating responsibility to the class. If we have actions that depend on the subtype of a class, it is easier to provide that feature in the parent class, and then the subclasses can (re)implement that feature.

Why **OCP** is Important
- Reduces changes to existing code, minimizing bugs.
- Allows adding new functionalities without modifying existing classes.
- Promotes use of polymorphism for scalability.

#### Violation

1. `Board` class that contains Rectangles and can calculate the area of the Rectangles.
	- Adding a new shape (e.g `Circle`) requires modifying this class, which violates **OCP**.

```php
class Rectangle
{
    public $width;
    public $height;
}

class Board
{
    public $rectangles = [];

    // ...

    public function calculateArea()
    {
        $area = 0;
        foreach ($this->rectangles as $rectangle) {
            $area += $rectangle->width * $rectangle->height;
        }

        return $area;
    }
}
```

2. `PaymentProcessor` class handles payment processing of different methods 
	- Adding a new payment method requires modifying this class, which violates **OCP**.

```php
class PaymentProcessor {
    public function payWithPayPal($amount) {
        // Process PayPal payment
    }

    public function payWithStripe($amount) {
        // Process Stripe payment
    }
}
```

#### Remedy

1. Now, new shapes can be added without modifying `Board`, keeping the code open for extension but closed for modification.

```php
interface Shape
{
    public function area();
}

class Rectangle implements Shape
{
    // ...

    public function area()
    {
        return $this->width * $this->height;
    }
}

class Circle implements Shape
{
    // ...

    public function area()
    {
        return $this->radius * $this->radius * pi();
    }
}

class Board
{
    public $shapes = [];

    // ...

    public function calculateArea()
    {
        $area = 0;
        foreach ($this->shapes as $shape) {
            $area+= $shape->area();
        }

        return $area;
    }
}
```

2. Now, new payment methods can be added without modifying `PaymentProcessor`, keeping the code open for extension but closed for modification.

```php
interface PaymentMethod {
    public function pay($amount);
}

class PayPalPayment implements PaymentMethod {
    public function pay($amount) {
        // Process PayPal payment
    }
}

class StripePayment implements PaymentMethod {
    public function pay($amount) {
        // Process Stripe payment
    }
}

class PaymentProcessor {
    public function pay(PaymentMethod $paymentMethod, $amount) {
        $paymentMethod->pay($amount);
    }
}
```


**Abstraction is a key**. In many ways this principle is at the heart of object oriented design.

## Liskov Substitution Principle (LSP)

> Derived classes must be substitutable for their base classes without altering the correctness of the program.

This principle says that every class that inherit from a parent class, must not replicate functionality already implemented in the parent class. Then the parent class should be able to be replaced by any of its subclasses in any region of the code without altering the correctness of the program.

Why **LSP** is Important
- Ensures that functionality is extended by derived classes without disrupting existing behavior.  
- Promotes abstraction through the use of interfaces.


#### Violation

1. Here, `Square` modifies `Rectangle`'s behavior unexpectedly, violating **LSP**.

```php
class Rectangle
{
    protected int $height;
    protected int $width;

    public function setWidth($w) { 
	    $this->width = $w; 
    }
    
    public function setHeight($h) { 
	    $this->height = $h; 
	}
	
    public function getArea() { 
	    return $this->height * $this->width; 
	}
}

class Square extends Rectangle
{
    public function setWidth($w) { 
	    $this->width = $w; 
	    $this->height = $w; 
    }
    
    public function setHeight($h) { 
	    $this->height = $h; 
	    $this->width = $h; 
	}
}

// calculate area
function areaOfRectangle() {
    $rectangle = new Rectangle();
    $rectangle->setWidth(7);
    $rectangle->setHeight(3);
    $rectangle->getArea(); // 21
}

function areaOfRectangle() {
    $rectangle = new Square();
    $rectangle->setWidth(7);
    $rectangle->setHeight(3);
    $rectangle->getArea(); // 9
}
```

2. Here, `ReadOnlyFile` modifies `File`'s behavior unexpectedly, violating **LSP**.

```php
class File
{
    public function read()
    {
       // ...
    }
 
    public function write()
    {
       // ...
    }
 }
 
 class ReadOnlyFile extends File
{
    public function write()
    {
        throw new ItsReadOnlyFileException();
    }
}
```
