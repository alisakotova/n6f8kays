<?php
require_once __DIR__ . '/../../Taxi/Car.php';

class CarTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /** @var \Taxi\Car */
    private $car;

    public function testProbabilityOfBreaking() {

        $this->car->mileage = 1000;
        $probability = $this->car->calculateProbabilityOfBreaking();

        $this->assertEquals(0.015, $probability);
    }

    public function testValidateCarBrand() {
        $this->assertTrue(\Taxi\Car::validateCarBrand(\Taxi\Car::BRAND_HENDAI, $message));

        $this->assertFalse(\Taxi\Car::validateCarBrand('brand', $message));
    }

    public function testValidateMileage() {
        $this->assertTrue(\Taxi\Car::validateMileage(10, $message));
        $this->assertFalse(\Taxi\Car::validateMileage(-10, $message));
    }
    protected function _before() {
        $this->car = new \Taxi\Car();
    }



}