
# AbstractFactory

## Definition 

Abstract Factory is used create series of related or dependent objects without specifying their concrete classes. Usually the created classes all implement the same interface. The client of the abstract factory does not care about how these objects are created, it just knows how they go together.

## Diagram 


```mermaid
classDiagram
    Client -- AbstractFactory : Uses
    AbstractFactory <|.. ConcreteFactory1 : Implements
    AbstractFactory <|.. ConcreteFactory2 : Implements
    AbstractFactory : +createProductA()
    AbstractFactory : +createProductB()
    Client -- AbstractProductA : Uses
    Client -- AbstractProductB : Uses
    AbstractProductA <|.. ProductA1 : Implements
    AbstractProductA <|.. ProductA2 : Implements
    AbstractProductB <|.. ProductB1 : Implements
    AbstractProductB <|.. ProductB2 : Implements
    ConcreteFactory1 : +createProductA()
    ConcreteFactory1 : +createProductB()
    ConcreteFactory2 : +createProductA()
    ConcreteFactory2 : +createProductB()
```
