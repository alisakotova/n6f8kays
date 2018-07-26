<?php
require_once __DIR__ . '/../../Taxi/Driver.php';
require_once __DIR__ . '/../../Taxi/Car.php';

class DriverTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \Taxi\Driver
     */
    private $driver;
    
    protected function _before() {
        $this->driver = new \Taxi\Driver();

        $car = new \Taxi\Car();

        $this->driver->car = $car;
    }


    // tests
    public function testCalculateFuelConsumptionForCurrentDay() {

        $fuelConsumption = $this->driver->calculateFuelConsumptionForCurrentDay();

        $this->assertEquals(7, $fuelConsumption);
    }

    public function testParkACar() {
        $car = $this->driver->parkACar();

        $this->assertAttributeEquals(null, 'car', $this->driver);

        $this->assertInstanceOf(\Taxi\Car::class, $car);
    }

    public function testCalculateDayDistance() {
        $this->assertEquals(70, $this->driver->calculateDayDistance());
    }
}