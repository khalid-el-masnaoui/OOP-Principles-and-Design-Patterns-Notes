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

#### Remedy

1. Now, `Manager` can work with any `Worker` that implements the Employee interface, adhering to **DIP**.
```php
interface Employee
{
    public function work();
}

class Worker implements Employee
{
    public function work() {}
}

class SpecializedWorker implements Employee
{
    public function work() {}
}
```

2. Now, `UserRepository` can work with any `Database` that implements the Database interface, adhering to **DIP**.

```php
interface Database {
    public function connect();
}

class MySQLDatabase implements Database {
    public function connect() {
        // MySQL connection
    }
}

class UserRepository {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }
}
```

# KISS - Keep It Simple, Stupid Principle

This principle suggests not involving complexity in the code and trying to avoid it as much as you can. This is because the more complex code is written, the more difficult it becomes to modify at any later point in time. Other acronyms are: Keep it short and simple, Keep it simple and smart, and Keep it simple and straightforward.


- **Write Readable Code:** Prioritize clarity over cleverness. Use clear variable names, concise logic, and avoid overly nested or complex expressions.


- **Avoid Unnecessary Abstraction:**  Don't introduce complex architectural patterns or multiple layers of abstraction when a simpler solution suffices. A well-named function might be more effective than a convoluted class hierarchy for a simple task.
    
- **Follow Consistent Coding Standards:**  Adhere to established PHP coding standards like `PSR-12`. This ensures uniformity and predictability in your codebase, making it easier for anyone to understand.
    
- **Refactor Regularly:**  If a function or method grows too large or performs too many responsibilities, break it down into smaller, more focused units. Smaller units are easier to test, debug, and maintain.
    
- **Focus on the Core Problem:**  Resist the urge to add features or functionalities that are not immediately required (**YAGNI - You Aren't Gonna Need It**). Solve the current problem with the simplest possible solution.


# DRY Principle

The **DRY (Don't Repeat Yourself)** principle is a fundamental software development principle that advocates for avoiding redundant code and logic. The core idea is that "Every piece of knowledge must have a single, unambiguous, authoritative representation within a system."


- **Functions:** Encapsulate repetitive operations within functions that can be called whenever needed.

```php
    function calculateTotalPrice($items) {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
```


- **Classes and Objects:** Use object-oriented programming to define classes that encapsulate data and behavior, promoting code reuse and organization.

```php
    class User {
        private $name;
        private $email;

        public function __construct($name, $email) {
            $this->name = $name;
            $this->email = $email;
        }

        public function getFullName() {
            return $this->name;
        }

        public function getEmailAddress() {
            return $this->email;
        }
    }
```

- **Traits:**  PHP traits allow you to reuse methods across different classes without using inheritance, providing a flexible way to share common functionality.
    
- **Configuration Files:**  Centralize application settings and configurations in a single file or a set of files to avoid repeating configuration values throughout the code.
    
- **Database Schema Design:** Design your database schema to minimize data redundancy and ensure data integrity.


**Note** : While highly beneficial, excessive or misapplied **DRY** can lead to over-abstraction, making the code more complex and harder to understand or modify in some cases.
# YAGNI - You Ain't Gonna Need It Principle

The YAGNI principle, is software development principle that advocates against adding functionality until it is demonstrably necessary.


- **Focus on Current Requirements:**  Implement only what is explicitly required by the current project scope or user stories. Avoid adding features or code that are based on speculative future needs, as these needs may never materialize or may change significantly.
    
- **Avoid Over-Engineering:**  Resist the temptation to build overly complex or abstract solutions for problems that are currently simple. While design patterns and architectural principles are valuable, applying them prematurely can lead to unnecessary complexity and maintenance overhead.
    
- **Prioritize Simplicity and Iteration:**  Strive for the simplest possible solution that meets the current requirements. If new requirements arise, the code can be extended or modified iteratively. This approach aligns with agile and lean methodologies, promoting flexibility and responsiveness to change.
    
- **Reduce Technical Debt:**  Implementing unnecessary features or complex abstractions can introduce technical debt that will need to be managed later. By adhering to **YAGNI**, developers can minimize this debt and focus on delivering value more efficiently.

**Example** 

Instead of creating a highly generic `UserFactory` class with multiple methods for creating different user types (e.g., `createAdminUser`, `createGuestUser`) when only `createStandardUser` is currently needed, a **YAGNI** approach would involve starting with a simple `User` class and a basic constructor. The factory pattern can be introduced later if and when the need for different user creation logic becomes evident.

# Tell, Don't Ask Principle 

The "**Tell, Don't Ask**" principle in object-oriented programming advocates for telling objects what to do rather than asking them for their internal state and then making decisions based on that information. This promotes **encapsulation** and reduces coupling.

#### Violation

- `OrderProcessor` queries the `Order` object for its items and discount to perform the calculation. This exposes the internal structure of `Order` and creates a dependency.

```php
class Order
{
    private array $items = [];
    private float $discount = 0.0;

    public function addItem(string $itemName, float $price)
    {
        $this->items[] = ['name' => $itemName, 'price' => $price];
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function applyDiscount(float $percentage)
    {
        $this->discount = $percentage;
    }
}

class OrderProcessor
{
    public function calculateTotalPrice(Order $order): float
    {
        $total = 0.0;
        foreach ($order->getItems() as $item) { // Asking for items
            $total += $item['price'];
        }

        if ($order->getDiscount() > 0) { // Asking for discount
            $total -= $total * $order->getDiscount();
        }

        return $total;
    }
}

$order = new Order();
$order->addItem("Laptop", 1200.00);
$order->addItem("Mouse", 25.00);
$order->applyDiscount(0.10); // 10% discount

$processor = new OrderProcessor();
$finalPrice = $processor->calculateTotalPrice($order);
echo "Final price (violating TDA): " . $finalPrice . PHP_EOL;

```

#### Remedy


- he `Order` object itself contains the logic to calculate its total price. 
- The client code simply "tells" the `Order` to perform this action by calling `calculateTotalPrice()`, without needing to know the internal details of how the calculation is done. 
- This improves encapsulation and makes the `Order` object more responsible for its own behavior.

```php
class Order
{
    private array $items = [];
    private float $discount = 0.0;

    public function addItem(string $itemName, float $price)
    {
        $this->items[] = ['name' => $itemName, 'price' => $price];
    }

    public function applyDiscount(float $percentage)
    {
        $this->discount = $percentage;
    }

    public function calculateTotalPrice(): float // Order calculates its own total
    {
        $total = 0.0;
        foreach ($this->items as $item) {
            $total += $item['price'];
        }

        if ($this->discount > 0) {
            $total -= $total * $this->discount;
        }

        return $total;
    }
}

// In the "Tell, Don't Ask" approach, the OrderProcessor might not even be needed for this specific task.
// The client code directly tells the Order to calculate its total.

$order = new Order();
$order->addItem("Laptop", 1200.00);
$order->addItem("Mouse", 25.00);
$order->applyDiscount(0.10); // 10% discount

$finalPrice = $order->calculateTotalPrice(); // Telling the order to calculate
echo "Final price (following TDA): " . $finalPrice . PHP_EOL;

```
# Others 

## Composition Over Inheritance Principle

The "**composition over inheritance**" principle suggests that classes should achieve code reuse and **polymorphic** behavior by containing instances of other classes (**composition**) rather than inheriting from a base or parent class (**inheritance**). 

- **"Has-a" vs. "Is-a" Relationships:**
    
    - **Inheritance**: represents an `is-a` relationship (e.g., a `Dog` is a `Animal`). This creates a strong, hierarchical coupling between classes.
    - **Composition**: represents a `has-a` relationship (e.g., a `Car` has an `Engine`). This allows objects to be built by combining independent components.
    
- **Advantages of Composition:**
    - **Flexibility and Reduced Coupling:** Composition allows for easier modification and extension of behavior without affecting the structure of the composing class. Changing a component only impacts the specific component, not the entire hierarchy.
    - **Avoidance of Inheritance Challenges:** Inheritance can lead to tight coupling, fragile base classes, and the "diamond problem" in languages that support multiple inheritance. Composition mitigates these issues.
    - **Better Modularity and Reusability:** Individual components can be developed and tested independently and then combined in various ways to create diverse functionalities.
    
- **How it works:**
    - Instead of extending a class, you create an instance of another class within your current class and delegate responsibilities to that instance.
    - Interfaces can be used to define contracts for the composed objects, ensuring that they provide the expected methods, allowing for **polymorphism without inheritance.**

**Example**

- Instead of a `Car` class inheriting from an `Engine` class (which doesn't make logical sense in a `is-a` relationship), the `Car` class would compose an `Engine` object:


```php
interface EngineInterface {
    public function start();
    public function stop();
}

class V6Engine implements EngineInterface {
    public function start() {
        echo "V6 Engine starting...\n";
    }
    public function stop() {
        echo "V6 Engine stopping...\n";
    }
}

class ElectricEngine implements EngineInterface {
    public function start() {
        echo "Electric Engine engaging...\n";
    }
    public function stop() {
        echo "Electric Engine disengaging...\n";
    }
}

class Car {
    private EngineInterface $engine;

    public function __construct(EngineInterface $engine) {
        $this->engine = $engine;
    }

    public function drive() {
        $this->engine->start();
        echo "Car is driving...\n";
    }

    public function park() {
        echo "Car is parking...\n";
        $this->engine->stop();
    }
}

$v6Car = new Car(new V6Engine());
$v6Car->drive();
$v6Car->park();

$electricCar = new Car(new ElectricEngine());
$electricCar->drive();
$electricCar->park();
```

In this example, the `Car` class has an `EngineInterface`, allowing it to work with any class that implements that interface, demonstrating the flexibility of composition.
