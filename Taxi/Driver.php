<?php
/**
 *
 */

namespace Taxi;

/**
 * Водитель
 *
 * Class Driver
 * @package Taxi
 */
class Driver {

    const TYPE_DEFAULT = 'default';

    const TYPE_PRO = 'pro';

    private static $driverTypes = [self::TYPE_DEFAULT, self::TYPE_PRO];

    /**
     * Сколько поездок в день осуществляет водитель
     * @var int
     */
    protected $tripsPerDay = 10;

    /**
     * Дистанция поездки
     * @var float
     */
    protected $tripDistanceKm = 7;

    /**
     * Коэффициент потребления топлива
     * @var float
     */
    protected $fuelConsumptionCoefficient = 1;

    /**
     * Машина, которой управляет водитель в течение дня
     * @var Car
     */
    public $car;

    /**
     * Уникальный номер водителя
     * @var int
     */
    public $id;

    /**
     * Сколько водитель потратил топлива в текущий день
     *
     * @return float
     */
    public function calculateFuelConsumptionForCurrentDay() : float {
        $result = 0;

        if (isset($this->car)) {
            $result = $this->tripsPerDay * $this->tripDistanceKm * $this->car->fuelConsumptionLitersPerKm * $this->fuelConsumptionCoefficient;
        }

        return $result;
    }

    /**
     * Водитель паркует машину
     * @return Car
     */
    public function parkACar() : Car {
        $car = $this->car;
        $this->car = null;

        return $car;
    }

    /**
     * Вычисление рассстояния, которое водитель проезжает в день, км
     * @return float
     */
    public function calculateDayDistance() : float {
        return $this->tripsPerDay * $this->tripDistanceKm;
    }


    public static function validateDriverType($type, &$message) : bool {
        if (!in_array($type, Driver::$driverTypes)) {
            $message = 'Неизвестный тип водителя: ' . $type;
            return false;
        }

        return true;
    }
}