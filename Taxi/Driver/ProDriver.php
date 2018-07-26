<?php
/**
 *
 */

namespace Taxi\Driver;
use Taxi\Driver;

/**
 * Профессиональный водитель
 *
 * Class ProDriver
 * @package Taxi
 */
class ProDriver extends Driver {

    protected $tripsPerDay = 13;

    protected $fuelConsumptionCoefficient = 0.8;

}