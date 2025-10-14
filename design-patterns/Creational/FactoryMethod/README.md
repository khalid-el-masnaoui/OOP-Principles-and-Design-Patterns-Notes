
# Factory Method

## Examples

- **[Car Builder](https://github.com/khalid-el-masnaoui/OOP-Principles-and-Design-Patterns-Notes/blob/main/design-patterns/Creational/Builder/CarBuilder.php)**
- **[Page Builder](https://github.com/khalid-el-masnaoui/OOP-Principles-and-Design-Patterns-Notes/blob/main/design-patterns/Creational/Builder/PageBuilder.php)**
- **[Car Builder - 2](https://github.com/khalid-el-masnaoui/OOP-Principles-and-Design-Patterns-Notes/blob/main/design-patterns/Creational/Builder/CarBuilder-2.php)**
- **[Vehicle Builder](https://github.com/khalid-el-masnaoui/OOP-Principles-and-Design-Patterns-Notes/blob/main/design-patterns/Creational/Builder/VehicleBuilder.php)**

## Definition 

**Factory Method** is a creational design pattern that provides an interface for creating objects in a superclass, but allows subclasses to alter the type of objects that will be created.

Instead of directly instantiating objects using constructors, client code interacts with a Creator object. The Creator's factory method is responsible for producing the appropriate Product object. The specific type of Product created is determined by the Concrete Creator subclass that implements the factory method. This allows the client to work with a common interface (the Product interface) without needing to know the concrete class of the object being created.

- At first glance, this change may look pointless: we just moved the constructor call from one part of the program to another. However, consider this: now you can override the factory method in a subclass and change the class of products being created by the method.

- There’s a slight limitation though: subclasses may return different types of products only if these products have a common base class or interface. Also, the factory method in the base class should have its return type declared as this interface.
