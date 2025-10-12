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
#### Remedy

1. By managing class **inheritance hierarchies** correctly, `Rectangle` and `Square` both follow **LSP**, allowing for correct substitutions. 

```php
interface Quadrilateral
{
    public function setHeight($h);
    public function setWidth($w);
    public function getArea();
}

class Rectangle implements Quadrilateral {}

class Square implements Quadrilateral {}
```

2. By managing class **inheritance hierarchies** correctly, `File` and `ReadOnlyFile` both follow **LSP**, allowing for correct substitutions. 

```php
interface FileRead
{
    public function read();
}

interface FileWrite
{
    public function write();
}

class File implements FileRead, FileWrite {}

class ReadOnlyFile implements FileRead {}
```


Following the Liskov Substitution Principle is a good indicator that you are following a correctly **hierarchy schema**. And if you don’t follow it, the unit tests for the superclass would never succeed for the subclasses.

## Interface Segregation Principle

> Make fine grained interfaces that are client specific.

This principle proposes to divide interfaces so they are more specific. A class can implement multiple interfaces simultaneously, we shouldn’t force clients to deploy methods unnecessary.


#### Violation
1. The classes `Manager` and `Developer`  are forced to implement unused methods:

```php
interface Worker {
    public function takeBreak();
    public function code();
    public function callToClient();
    public function attendMeetings();
    public function getPaid();
}

class Manager implements Worker
{
    public function code() { 
	    return false; 
	}
}

class Developer implements Worker
{
    public function callToClient() { 
	    echo $swearWord; 
	}
}
```

2. `CreditNote` only uses `getCSV`method, but is forced to implement `getPDF` too.

```php
interface Exportable
{
    public function getPDF();
    public function getCSV();
}

class Invoice implements Exportable
{
    public function getPDF() {
        // ...
    }
    public function getCSV() {
        // ...
    }
}

class CreditNote implements Exportable
{
    public function getPDF() {
        throw new \NotUsedFeatureException();
    }
    public function getCSV() {
        // ...
    }
}
```
#### Remedy

1. We create more grained interfaces.

```php
interface Worker
{
    public function takeBreak();
    public function getPaid();
}

interface Coder {
    public function code();
}

interface ClientFacer {
    public function callToClient();
    public function attendMeetings();
}

class Developer implements Worker, Coder {}

class Manager implements Worker, ClientFacer {}
```

2. We create more grained interfaces.

```php
interface PDFExportable
{
    public function getPDF();
}

interface CSVExportable
{
    public function getCSV();
}

class Invoice implements PDFExportable, CSVExportable
{
    public function getPDF() {
        // ...
    }
    public function getCSV() {
        // ...
    }
}

class CreditNote implements CSVExportable
{
    public function getCSV() {
        // ...
    }
}
```


## Dependency Inversion Principle

> Depend on abstractions, not on concretions.

As this principle could be difficult to understand, lets review what the principle states:

> High level modules should not depend on low-level modules, both should depend on abstractions. Abstractions should not depend on details. Details should depend on abstractions.

This principle inverts the way some people may think about OOP, dictating that both classes and subclasses must depend on the same abstraction.

#### Violation

1. `Manager`is tied to `Worker` implementation , what if we need to add a new type of workers?

```php
class Worker
{
    public function work() {}
}

class Manager
{
    private $worker;

    public function setWorker(Worker $w)
    {
        $this->worker = $w;
    }

    public function manage()
    {
        $this->worker->work();
    }
}
```

2. `UserRepository`is tied to `MySQLDatabase`, what if we need to add a database implementation?

```php
class MySQLDatabase {
    public function connect() {
        // MySQL connection
    }
}

class UserRepository {
    private $db;

    public function __construct(MySQLDatabase $db) {
        $this->db = $db;
    }
}
```
