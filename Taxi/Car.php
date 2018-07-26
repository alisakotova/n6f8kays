<?php
/**
 *
 */

namespace Taxi;


/**
 * Автомобиль
 *
 * Class Car
 * @package Taxi
 */
class Car {

    const BRAND_LUDA = 'Luda';

    const BRAND_HOMBA = 'Homba';

    const BRAND_HENDAI = 'Hendai';

    private static $carBrands = [self::BRAND_LUDA, self::BRAND_HOMBA, self::BRAND_HENDAI];

    /**
     * Потребление топлива в литрах на км
     * @var float
     */
    public $fuelConsumptionLitersPerKm = 0.1;

    /**
     * Пробег автомобиля, км
     * @var float
     */
    public $mileage = 0;

    /**
     * Вероятность поломки нового автомобиля
     * @var float
     */
    public $probabilityOfBreakingByDefault = 0.005;

    /**
     * Значение, на которое увеличивается вероятность поломки автомобиля через каждые 1000 км пробега
     * @var float
     */
    protected $probabilityOfBreakingByEvery1000Km = 0.01;

    /**
     * Сломан ли автобомиль
     * @var bool
     */
    public $isBroken = false;

    /**
     * Сколько дней осталось до конца ремонта машины
     * @var int
     */
    public $remainingDaysUntilFix = 0;

    /**
     * Уникальный номер машины
     * @var int
     */
    public $id;



    /**
     * Вычисление вероятности поломки автомобиля с учетом пробега
     * @return float
     */
    public function calculateProbabilityOfBreaking() : float {

        return $this->probabilityOfBreakingByDefault + $this->mileage / 1000 * $this->probabilityOfBreakingByEvery1000Km;
    }


    public static function validateCarBrand($brand, &$message) : bool {
        if (!in_array($brand, Car::$carBrands)) {
            $message = 'Неизвестный бренд автомобиля: ' . $brand;
            return false;
        }

        return true;
    }

    public static function validateMileage($mileage, &$message) : bool {
        if ($mileage < 0) {
            $message = 'Пробег автомобиля не может быть меньше 0 км, получено значение: ' . $mileage;
            return false;
        }

        return true;
    }


}