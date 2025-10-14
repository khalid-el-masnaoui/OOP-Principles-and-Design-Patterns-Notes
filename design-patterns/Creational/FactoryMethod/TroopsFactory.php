<?php


namespace DesignPatterns\Creational;

/**
 * Base factory class contains a factory method and some business logic
 */
interface TroopsFactory
{
    public function makeTroops();
}


/**
 * Concrete factories extend that method to produce different kinds of troops
 */
class InfantryFactory implements TroopsFactory
{
    public function makeTroops()
    {
        return new XPinfantry();
    }
}

class CavalryFactory implements TroopsFactory
{
    public function makeTroops()
    {
        return new XPCavalry();
    }
}


/**
 * Troops interface declares behaviors of various types of Troops
*/
interface Troops
{
    public function attack();
}

class XPinfantry implements Troops
{
    public function attack()
    {
        echo 'Infantry attack, attack power: 10~ '.PHP_EOL;
    }
}

class XPcavalry implements Troops
{
    public function attack()
    {
        echo 'Cavalry attack, attack power: 30~ '.PHP_EOL;
    }
}

# Client code example
$troopsType = 'cavalry'; // taken from configuration for example

switch ($troopsType) {
    case 'infantry':
        $troopsFactory = new InfantryFactory();
        break;
    case 'cavalry':
        $troopsFactory = new CavalryFactory();
        break;
}

$troopsFactory->makeTroops()->attack();
/* Output: Cavalry attack, attack power: 30~  */
