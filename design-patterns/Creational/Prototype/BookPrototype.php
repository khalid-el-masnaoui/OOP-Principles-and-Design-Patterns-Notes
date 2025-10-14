<?php

namespace DesignPatterns\Creational;

interface BookPrototype
{
    public function __clone();
}

class Book implements BookPrototype
{
    private string $title;
    private string $author;
    private object $publisher; // Example of a child object

    public function __construct(string $title, string $author, object $publisher)
    {
        $this->title = $title;
        $this->author = $author;
        $this->publisher = $publisher;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function __clone()
    {
        // Deep copy the publisher object if necessary
        $this->publisher = clone $this->publisher;
    }
}

class Publisher
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

# Client code example
$originalPublisher = new Publisher("Awesome Books Inc.");
$originalBook = new Book("The Great Adventure", "John Doe", $originalPublisher);

$clonedBook = clone $originalBook;
$clonedBook->setTitle("The New Adventure"); // Modify the cloned object

echo $originalBook->getTitle(); // Output: The Great Adventure
echo $clonedBook->getTitle();   // Output: The New Adventure

// Verify that the publisher object was also cloned (deep copy)
if ($originalBook->publisher !== $clonedBook->publisher) {
    echo "Publisher objects are distinct.";
}

?>
