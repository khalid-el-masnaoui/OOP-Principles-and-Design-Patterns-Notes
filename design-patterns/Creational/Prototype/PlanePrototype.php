<?php

namespace DesignPatterns\Creational;

abstract class PlanePrototype
{
    abstract public function cloned();
}

class Plane extends PlanePrototype
{
    public $color;

    public function cloned()
    {
        return clone $this;
    }
}

# Client code example
$plane1 = new Plane();
$plane1->color = 'silver';
$plane2 = $res1->cloned();

echo $plane1->color.PHP_EOL; // Output: silver
echo $plane2->color.PHP_EOL; // Output: silver
