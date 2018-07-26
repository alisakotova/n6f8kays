<?php
/**
 *
 */
namespace Taxi\Car;
use Taxi\Car;


/**
 * Автомобиль бренда "Luda"
 *
 * Class Luda
 * @package Taxi
 */
class Luda extends Car {

    public $probabilityOfBreakingByDefault = 0.015;

    protected $probabilityOfBreakingByEvery1000Km = 0.03;

}