<?php

require_once __DIR__ . '/../../Taxi/ParkingPlace.php';
require_once __DIR__ . '/../../Taxi/Car.php';

class ParkingPlaceTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /** @var \Taxi\ParkingPlace */
    private $parkingPlace;

    /** @var \Taxi\Car */
    private $car;
    
    protected function _before() {
        $this->parkingPlace = new \Taxi\ParkingPlace();
        $this->car = new \Taxi\Car();
    }


    // tests
    public function testParkACar() {
        $this->assertAttributeEquals(null, 'car', $this->parkingPlace);

        $this->parkingPlace->parkACar($this->car);

        $this->assertAttributeEquals($this->car, 'car', $this->parkingPlace);
    }

    public function testIsFree() {
        $this->parkingPlace->car = null;
        $this->assertTrue($this->parkingPlace->isFree());

        $this->parkingPlace->parkACar($this->car);
        $this->assertFalse($this->parkingPlace->isFree());
    }

    public function testCanTakeOutACar() {
        $this->parkingPlace->car = null;
        $this->parkingPlace->parkACar($this->car);

        $this->parkingPlace->car->isBroken = true;
        $this->assertFalse($this->parkingPlace->canTakeOutACar());

        $this->parkingPlace->car->isBroken = false;
        $this->assertTrue($this->parkingPlace->canTakeOutACar());

        $this->parkingPlace->takeOutACar();
        $this->assertFalse($this->parkingPlace->canTakeOutACar());
    }

    public function testTakeOutACar() {
        $this->parkingPlace->car = null;
        $this->parkingPlace->parkACar($this->car);

        $car = $this->parkingPlace->takeOutACar();
        $this->assertAttributeEquals(null, 'car', $this->parkingPlace);
        $this->assertInstanceOf(\Taxi\Car::class, $car);
    }


}