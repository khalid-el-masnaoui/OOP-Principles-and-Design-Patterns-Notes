<?php

namespace DesignPatterns\Creational;

/**
 * Abstract Factory defines an interface for creating all distinct products,
 * but leaves the actual product creation to concrete factory classes
 */
 interface BarrackFactory
{
    public function weapon();

    public function armor();

    public function go($Barracks);
}
 
 /**
 * Each factory type corresponds to a certain product variety
 */
class OnlyWeaponBarrackFactory implements BarrackFactory
{
    public function weapon()
    {
        return new Arms();
    }

    public function armor()
    {
        return null;
    }

    public function go($Barracks)
    {
        $weapon = $this->weapon();
        $armor = $this->armor();
        if ($weapon) {
            $weapon->make();
        }
        if ($armor) {
            $armor->make();
        }
        $Barracks->make();
    }
}

class OnlyArmorBarrackFactory implements BarrackFactory
{
    public function weapon()
    {
        return null;
    }

    public function armor()
    {
        return new Armor();
    }

    public function go($Barracks)
    {
        $weapon = $this->weapon();
        $armor = $this->armor();
        if ($weapon) {
            $weapon->make();
        }
        if ($armor) {
            $armor->make();
        }
        $Barracks->make();
    }
}

class WeaponAndArmorBarrackFactory implements BarrackFactory
{

    public function weapon()
    {
        return new Weapon();
    }

    public function armor()
    {
        return new Armor();
    }

    public function go($Barracks)
    {
       $weapon = $this->weapon();
        $armor = $this->armor();
        if ($weapon) {
            $weapon->make();
        }
        if ($armor) {
            $armor->make();
        }
        $Barracks->make();
    }
}
 
/**
 * The base interface for barracks (products) family
 */
interface Barracks
{
    public function make();
}

class Infantry implements Barracks
{
    public function make()
    {
        echo ', Infantry Defense! '.PHP_EOL;
    }
}

class Cavalry implements Barracks
{
    public function make()
    {
        echo '，Cavalry Attack! '.PHP_EOL;
    }
}

/**
 * The base interface for equipement (products) family
 */
interface Equipment
{
    public function make();
}


class Weapon implements Equipment
{
    public function make()
    {
        echo 'Equip a weapon, attack power +100 ';
    }
}

class Armor implements Equipment
{
    public function make()
    {
        echo 'Equip armor, defense +50 ';
    }
}




# Client code example
// the factory is selected based on the environment or configuration parameters
$equipementType = 'armor';
switch ($templateEngine) {
    case 'weapon':
        $barrackFactory = new OnlyWeaponBarrackFactory();
        break;
    case 'armor':
        $barrackFactory = new OnlyArmorBarrackFactory();
        break;
    case 'weapon&armor':
        $barrackFactory = new WeaponAndArmorBarrackFactory();
        break;
}

$soldiersCommand = 'attack';
switch ($soldiersCommand) {
    case 'attack':
        $barrackFactory->go(new Cavalry());
        break;
    case 'defend':
        $barrackFactory->go(new Infantry());
        break;
}

/* Output: Equip a weapon, attack power +100，Cavalry Attack! */

