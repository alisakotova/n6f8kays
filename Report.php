<?php
/**
 *
 */

/**
 * Отчет о моделировании работы таксопарка
 *
 * Class Report
 */
class Report {

    /**
     * Данные отчета
     *
     * @var array
     */
    public $data;

    /**
     * Добавление в отчет данных о водителях
     *
     * @param \Taxi\Driver[] $drivers
     */
    public function addInitialDriversData(array $drivers) {
        /** @var \Taxi\Driver $driver */
        foreach ($drivers as $driver) {
            $type = '';
            if ($driver instanceof \Taxi\Driver\ProDriver) {
                $type = \Taxi\Driver::TYPE_PRO;
            } elseif ($driver instanceof \Taxi\Driver) {
                $type = \Taxi\Driver::TYPE_DEFAULT;
            }

            $this->data['drivers'][] = ['id' => $driver->id, 'type' => $type];
        }
    }

    /**
     * Добавление в отчет изначальных данных автомобилей
     *
     * @param \Taxi\Car[] $cars
     */
    public function addInitialCarsData(array $cars) {
        /** @var \Taxi\Car $car */
        foreach ($cars as $car) {
            $brand = '';

            if ($car instanceof \Taxi\Car\Homba) {
                $brand = \Taxi\Car::BRAND_HOMBA;
            } elseif ($car instanceof \Taxi\Car\Luda) {
                $brand = \Taxi\Car::BRAND_LUDA;
            } elseif ($car instanceof \Taxi\Car\Hendai) {
                $brand = \Taxi\Car::BRAND_HENDAI;
            }

            $this->data['cars'][] = ['id' => $car->id, 'brand' => $brand, 'km' => $car->mileage];
        }
    }

    /**
     * Добавление в отчет данных об эксплуатации машин водителями за заданный день
     * @param int $dayNumber
     * @param \Taxi\Driver[] $drivers
     */
    public function addDayDriversData($dayNumber, array $drivers) {
        /** @var \Taxi\Driver $driver */
        foreach ($drivers as $driver) {
            $carId = null;
            $fuelForThisDay = 0;
            if (isset($driver->car)) {
                $carId = $driver->car->id;
                $fuelForThisDay = $driver->calculateFuelConsumptionForCurrentDay();
            }

            $this->data['days'][$dayNumber]['drivers'][] = ['id' => $driver->id, 'car_id' => $carId, 'fuel' => $fuelForThisDay];
        }
    }

    /**
     * Добавление в отчет данных о машинах за заданный день
     * @param int $dayNumber
     * @param \Taxi\ParkingPlace[] $parkingPlaces
     */
    public function addDayCarsData($dayNumber, array $parkingPlaces) {
        /** @var \Taxi\ParkingPlace $parkingPlace */
        foreach ($parkingPlaces as $parkingPlace) {
            if (!$parkingPlace->isFree()) {
                $this->data['days'][$dayNumber]['cars'][] =
                    ['id' => $parkingPlace->car->id, 'km' => $parkingPlace->car->mileage, 'is_broken' => $parkingPlace->car->isBroken];
            }
        }
    }

}