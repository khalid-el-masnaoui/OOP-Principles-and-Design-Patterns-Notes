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
