<?php
/**
 *
 */

namespace Taxi;


/**
 * Парковочное место
 *
 * Class ParkingPlace
 * @package Taxi
 */
class ParkingPlace {

    /**
     * Машина, которая припаркована в этом парковочном месте
     * @var Car
     */
    public $car;

    /**
     * Припарковать машину на это место
     * @param Car $car
     */
    public function parkACar(Car $car) {

        $this->car = $car;
    }

    /**
     * Изъять припаркованную машину
     * @return Car
     */
    public function takeOutACar() : Car {

        $car = $this->car;
        $this->car = null;

        return $car;
    }

    /**
     * Можно ли взять машину из этого парковочного места
     * @return bool
     */
    public function isFree() : bool {

        return !isset($this->car);
    }

    /**
     * Можно ли взять машину из этого парковочного места
     * @return bool
     */
    public function canTakeOutACar() : bool {

        return (!$this->isFree() && !$this->car->isBroken);

    }
}