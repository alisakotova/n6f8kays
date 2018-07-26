<?php

require_once __DIR__ . '/../../Taxi/Car/Luda.php';

class LudaTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testProbabilityOfBreaking() {
        $car = new \Taxi\Car\Luda();

        $car->mileage = 1000;
        $probability = $car->calculateProbabilityOfBreaking();

        $this->assertEquals(0.045, $probability);
    }
}