<?php
/**
 *
 */

namespace Taxi;
require_once __DIR__ . '/Exception.php';

/**
 * Таксопарк
 *
 * Class TaxiStation
 * @package Taxi
 */
class TaxiStation {

    /**
     * Количество парковочных мест таксопарка
     * @var int
     */
    public $parkingPlacesNumber = 0;

    /**
     * Парковочные места таксопарка
     * @var ParkingPlace[]
     */
    public $parkingPlaces = [];

    /**
     * Водители таксопарка
     * @var Driver[]
     */
    public $drivers = [];


    /**
     * Симуляция поломки машины
     * @param \Taxi\Car $car
     */
    public function simulateCarBreaking($car) {

        $probabilityOfBreaking = $car->calculateProbabilityOfBreaking();
        $randomMultiplier = $this->calculateRandomBoundaryMultiplier($probabilityOfBreaking);
        // приведение вероятности к целому значению и получение целого случайного числа
        $isCarBreaking = mt_rand(1, $randomMultiplier) <= $probabilityOfBreaking * $randomMultiplier;
        if ($isCarBreaking) {
            $car->isBroken = true;
            $car->remainingDaysUntilFix = 3;
        }

    }

    /**
     * Симпуляция прогресса ремонта машины в день
     * @param \Taxi\Car $car
     */
    public function simulateCarFixing($car) {
        $car->remainingDaysUntilFix--;
        if ($car->remainingDaysUntilFix == 0) {
            $car->isBroken = false;
        }
    }

    /**
     * Симуляция обновления состояний машин в парке
     */
    public function refreshCarsState() {
        foreach ($this->parkingPlaces as $parkingPlace) {
            if (!$parkingPlace->isFree()) {

                if ($parkingPlace->car->isBroken) {
                    $this->simulateCarFixing($parkingPlace->car);
                } else {
                    $this->simulateCarBreaking($parkingPlace->car);
                }
            }
        }
    }

    /**
     * Симуляция начала рабочего дня таксопарка: водилели берут себе машину на день
     */
    public function simulateDriversTakeCars() {

        // Водители каждый раз приходят на работу в случайном порядке
        shuffle($this->drivers);

        $i = 0;
        foreach ($this->drivers as $driver) {

            // водитель ищет первую машину с начала парковки, которую можно взять
            while ($i < $this->parkingPlacesNumber && !$this->parkingPlaces[$i]->canTakeOutACar()) {
                $i++;
            }

            // просмотрены все парковочные места и больше нет машин, которые можно взять
            if ($i >= $this->parkingPlacesNumber) {
                break;
            }

            // водитель берет первую попавшуюся мащину, которую можно взять
            $driver->car = $this->parkingPlaces[$i]->takeOutACar();

        }
    }

    /**
     * Симуляция конца рабочего дня таксопарка: водители возвращают машины в парк
     */
    public function simulateDriversParkCars() {

        // Водители каждый раз возвращаются в таксопарк в случайном порядке
        shuffle($this->drivers);

        $i = 0;
        foreach ($this->drivers as $driver) {

            if (isset($driver->car)) {
                // водитель ищет первое свободное место на парковке
                while (!$this->parkingPlaces[$i]->isFree()) {
                    $i++;
                }

                // обновляется пробег автомобиля с учетом расстояния поездок за текущий день
                $driver->car->mileage += $driver->calculateDayDistance();

                // водитель паркует тачку
                $this->parkingPlaces[$i]->car = $driver->parkACar();
            }

        }
    }

    /**
     * Инициализация парковочных мест
     */
    public function initializeParkingPlaces() {
        for ($i = 0; $i < $this->parkingPlacesNumber; $i++) {
            $this->parkingPlaces[] = new \Taxi\ParkingPlace();
        }
    }

    /**
     * Инициализация списка водителей
     *
     * @param array $drivers массив данных водителей
     * @throws \Taxi\Exception
     */
    public function initializeDrivers(array $drivers) {
        $id = 1;
        foreach ($drivers as $item) {

            if (!\Taxi\Driver::validateDriverType($item['type'], $message)) {
                throw new \Taxi\Exception($message);
            };

            $driver = null;
            switch ($item['type']) {
                case \Taxi\Driver::TYPE_PRO:
                    $driver = new \Taxi\Driver\ProDriver();
                    break;
                case \Taxi\Driver::TYPE_DEFAULT:
                    $driver = new \Taxi\Driver();
                    break;
                default:
                    break;
            }

            $driver->id = $id;
            $id++;

            $this->drivers[] = $driver;
        }
    }

    /**
     * Инициализация машин таксопарка
     *
     * @param array $cars массив данных машин
     * @throws \Taxi\Exception
     */
    public function initializeCars(array $cars) {
        $id = 1;
        $i = 0;
        foreach ($cars as $item) {
            if (!\Taxi\Car::validateCarBrand($item['brand'], $message)){
                throw new Exception($message);
            }

            if (!\Taxi\Car::validateMileage($item['km'], $message)) {
                throw new Exception($message);
            }

            $car = null;
            switch ($item['brand']) {
                case \Taxi\Car::BRAND_HENDAI:
                    $car = new \Taxi\Car\Hendai();
                    break;
                case \Taxi\Car::BRAND_HOMBA:
                    $car = new \Taxi\Car\Homba();
                    break;
                case \Taxi\Car::BRAND_LUDA:
                    $car = new \Taxi\Car\Luda();
                    break;
                default:
                    break;
            }

            $car->id = $id;
            $car->mileage = $item['km'];
            $id++;

            $this->parkingPlaces[$i]->car = $car;
            $i++;
        }
    }

    public static function validateParkingPlacesNumber($parkingPlacesNumber, &$message) : bool {
        if ($parkingPlacesNumber < 1) {
            $message = 'Количество парковочных мест работающего таксопарка не может быть меньше 1';
            return false;
        }

        return true;
    }

    public static function validateCarsNumber($carsNumber, &$message) : bool {
        if ($carsNumber < 1) {
            $message = 'Количество машин работающего таксопарка не может быть меньше 1';
            return false;
        }

        return true;
    }

    public static function validateDriversNumber($driversNumber, &$message) : bool {
        if ($driversNumber < 1) {
            $message = 'Количество водителей работающего таксопарка не может быть меньше 1';
            return false;
        }

        return true;
    }

    public static function validateCarsNumberAndParkingPlacesNumber($carsNumber, $parkingPlacesNumber, &$message) : bool {
        if ($parkingPlacesNumber < $carsNumber) {
            $message = 'Количество парковочных мест работающего таксопарка не может быть меньше количества машин';
            return false;
        }

        return true;
    }

    /**
     * Получить множитель, чтобы сделать границы отрезка получения случайного числа целочисленными, и запрашивать случайное целое число
     *
     * @param float $probability вероятность
     * @return int
     */
    private function calculateRandomBoundaryMultiplier($probability) : int {
        $multiplier = 1;

        while ($probability < 1) {
            $multiplier *= 10;
            $probability *= $multiplier;
        }

        return $multiplier;
    }


}