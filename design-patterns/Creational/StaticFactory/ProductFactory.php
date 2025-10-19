<?php

namespace DesignPatterns\Creational;

// Define an interface for the products
interface Product
{
    public function getName(): string;
}

// Implement concrete product classes
class ConcreteProductA implements Product
{
    public function getName(): string
    {
        return "Product A";
    }
}

class ConcreteProductB implements Product
{
    public function getName(): string
    {
        return "Product B";
    }
}

// Create a Static Factory class
class ProductFactory
{
    public static function createProduct(string $type): Product
    {
        switch ($type) {
            case 'A':
                return new ConcreteProductA();
            case 'B':
                return new ConcreteProductB();
            default:
                throw new InvalidArgumentException("Unknown product type: " . $type);
        }
    }
}

// Client code using the Static Factory
$productA = ProductFactory::createProduct('A');
echo $productA->getName(); // Output: Product A

$productB = ProductFactory::createProduct('B');
echo $productB->getName(); // Output: Product B
