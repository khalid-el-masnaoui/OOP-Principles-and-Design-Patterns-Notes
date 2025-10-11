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


