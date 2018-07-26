<?php

require_once __DIR__ . '/../../Taxi/TaxiStation.php';
require_once __DIR__ . '/../../Taxi/ParkingPlace.php';
require_once __DIR__ . '/../../Taxi/Car.php';
require_once __DIR__ . '/../../Taxi/Car/Homba.php';
require_once __DIR__ . '/../../Taxi/Driver.php';
require_once __DIR__ . '/../../Taxi/Driver/ProDriver.php';

class TaxiStationTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /** @var \Taxi\TaxiStation */
    private $taxiStation;

    /** @var \Taxi\Car */
    private $car;
    
    protected function _before() {

        $this->taxiStation = new \Taxi\TaxiStation();

        $this->car = new \Taxi\Car();
    }


    // tests
    public function testSimulateCarBreaking() {
        $this->car->mileage = 0;
        $this->car->probabilityOfBreakingByDefault = 1;
        $this->taxiStation->simulateCarBreaking($this->car);

        $this->assertAttributeEquals(true, 'isBroken', $this->car);
        $this->assertAttributeEquals(3, 'remainingDaysUntilFix', $this->car);
    }

    public function testSimulateCarFixing() {
        $this->car->isBroken = true;
        $this->car->remainingDaysUntilFix = 3;

        $this->taxiStation->simulateCarFixing($this->car);
        $this->assertAttributeEquals(true, 'isBroken', $this->car);
        $this->assertAttributeEquals(2, 'remainingDaysUntilFix', $this->car);
        $this->taxiStation->simulateCarFixing($this->car);
        $this->taxiStation->simulateCarFixing($this->car);
        $this->assertAttributeEquals(false, 'isBroken', $this->car);
        $this->assertAttributeEquals(0, 'remainingDaysUntilFix', $this->car);
    }

    public function testSimulateDriversTakeCars() {
        $this->taxiStation->parkingPlacesNumber = 7;
        for ($i = 0; $i < $this->taxiStation->parkingPlacesNumber; $i++) {
            $this->taxiStation->parkingPlaces[$i] = new \Taxi\ParkingPlace();
            $car = new \Taxi\Car\Homba();
            $this->taxiStation->parkingPlaces[$i]->parkACar($car);
        }

        $this->taxiStation->parkingPlaces[2]->car->isBroken = true;
        $this->taxiStation->parkingPlaces[5]->car->isBroken = true;

        for ($i = 0; $i < $this->taxiStation->parkingPlacesNumber; $i++) {
            $this->taxiStation->drivers[] = new Taxi\Driver();
        }

        $this->taxiStation->simulateDriversTakeCars();

        for ($i = 0; $i < 5; $i++) {
            $this->assertInstanceOf(\Taxi\Car::class, $this->taxiStation->drivers[$i]->car);
        }

        for ($i = 5; $i < $this->taxiStation->parkingPlacesNumber; $i++) {
            $this->assertEmpty($this->taxiStation->drivers[$i]->car);
        }

        $this->assertTrue($this->taxiStation->parkingPlaces[0]->isFree());
        $this->assertTrue($this->taxiStation->parkingPlaces[1]->isFree());
        $this->assertTrue(!$this->taxiStation->parkingPlaces[2]->isFree());
        $this->assertTrue($this->taxiStation->parkingPlaces[3]->isFree());
        $this->assertTrue($this->taxiStation->parkingPlaces[4]->isFree());
        $this->assertTrue(!$this->taxiStation->parkingPlaces[5]->isFree());
        $this->assertTrue($this->taxiStation->parkingPlaces[6]->isFree());
    }

    public function testSimulateDriversParkCars() {
        $this->taxiStation->parkingPlacesNumber = 1;
        $this->taxiStation->initializeParkingPlaces();

        for ($i = 0; $i < $this->taxiStation->parkingPlacesNumber; $i++) {
            $this->taxiStation->drivers[] = new Taxi\Driver();
        }

        $this->taxiStation->drivers[0]->car = new \Taxi\Car\Homba();
        $this->assertEquals(0, $this->taxiStation->drivers[0]->car->mileage);
        $this->taxiStation->simulateDriversParkCars();

        $this->assertEmpty($this->taxiStation->drivers[0]->car);

        $this->assertEquals(70, $this->taxiStation->parkingPlaces[0]->car->mileage);

    }


}