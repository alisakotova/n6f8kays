<?php
require_once __DIR__ . '/../../Taxi/Driver/ProDriver.php';

class ProDriverTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /** @var \Taxi\Driver\ProDriver */
    private $proDriver;
    
    protected function _before() {
        $this->proDriver = new \Taxi\Driver\ProDriver();
    }


    // tests
    public function testCalculateDayDistance() {
        $this->assertEquals(91, $this->proDriver->calculateDayDistance());

    }
}