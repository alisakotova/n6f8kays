<?php
/**
 *
 */

include_once "data.php";

require_once  __DIR__ . '/Taxi/TaxiStation.php';
require_once  __DIR__ . '/Taxi/ParkingPlace.php';
require_once  __DIR__ . '/Taxi/Exception.php';
require_once  __DIR__ . '/Taxi/Driver.php';
require_once  __DIR__ . '/Taxi/Driver/ProDriver.php';
require_once  __DIR__ . '/Taxi/Car.php';
require_once  __DIR__ . '/Taxi/Car/Luda.php';
require_once  __DIR__ . '/Taxi/Car/Homba.php';
require_once  __DIR__ . '/Taxi/Car/Hendai.php';

require_once  __DIR__ . '/Report.php';

const TAXISTATION_WORKDAYS_NUMBER = 10;

$dataArray = json_decode($dataJson,  true);

$errorMessages = [];
/** Валидация данных и инициализация */
if (!\Taxi\TaxiStation::validateParkingPlacesNumber($dataArray['park']['places'], $message)) {
    $errorMessages[] = $message;
};

$carsNumber = count($dataArray['cars']);
if (!\Taxi\TaxiStation::validateCarsNumber($carsNumber, $message)) {
    $errorMessages[] = $message;
}

if (!\Taxi\TaxiStation::validateCarsNumberAndParkingPlacesNumber($carsNumber, $dataArray['park']['places'], $message)) {
    $errorMessages[] = $message;
}

if (!\Taxi\TaxiStation::validateDriversNumber(count($dataArray['drivers']), $message)) {
    $errorMessages[] = $message;
}

if (!empty($errorMessages)) {
    exit(join("\n", $errorMessages));
}

$taxiStation = new \Taxi\TaxiStation();
$taxiStation->parkingPlacesNumber = $dataArray['park']['places'];
$taxiStation->initializeParkingPlaces();

try {
    $taxiStation->initializeDrivers($dataArray['drivers']);
} catch (Exception $e) {
    $errorMessages[] = $e->getMessage();
}

try {
    $taxiStation->initializeCars($dataArray['cars']);
} catch (Exception $e) {
    $errorMessages[] = $e->getMessage();
}

if (!empty($errorMessages)) {
    exit(join("\n", $errorMessages));
}

/** Инициализация отчета о работе таксопарка */
$report = new Report();
$report->addInitialDriversData($taxiStation->drivers);
$cars = [];
/** @var \Taxi\ParkingPlace $parkingPlace */
foreach ($taxiStation->parkingPlaces as $parkingPlace) {
    if (!$parkingPlace->isFree()) {
        $cars[] = $parkingPlace->car;
    }
}
$report->addInitialCarsData($cars);

/** Начало симуляции работы таксопарка */
for ($currentDay = 1; $currentDay <= TAXISTATION_WORKDAYS_NUMBER; $currentDay++) {
    $taxiStation->simulateDriversTakeCars();  // начало рабочего дня - водители берут машины

    // длится рабочий день

    $report->addDayDriversData($currentDay, $taxiStation->drivers);  // информация о водителях за текущий день заносится в отчет

    $taxiStation->simulateDriversParkCars(); // в конце дня водители возвращают машины на место

    $report->addDayCarsData($currentDay, $taxiStation->parkingPlaces); // информация о машинах за текущий день заносится в отчет

    $taxiStation->refreshCarsState(); // обновление состояний машин в парке
}

// вывод отчета - табличка
echo '<html><head><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous"></head>
<body><h3>Водители, входные данные</h3><table class="table"><tr>';
foreach ($report->data['drivers'] as $driver) {
    echo "<td>id: {$driver['id']}<br>type: {$driver['type']}</td>";
}
echo '</tr></table>';

echo '<h3>Машины, входные данные</h3><table class="table"><tr>';
foreach ($report->data['cars'] as $car) {
    echo "<td>id: {$car['id']}<br>brand: {$car['brand']}<br>km: {$car['km']}</td>";
}
echo '</tr></table>';

echo '<h3>Водители по дням</h3><table class="table">';
foreach ($report->data['days'] as $day => $dayData) {
    echo "<tr><td>{$day}</td>";

    foreach ($dayData['drivers'] as $driver) {
        echo "<td>id: {$driver['id']}<br>car id: {$driver['car_id']}<br>fuel: {$driver['fuel']}</td>";
    }

    echo "</tr>";

}
echo '</table>';

echo '<h3>Машины по дням</h3><table class="table">';
foreach ($report->data['days'] as $day => $dayData) {
    echo "<tr><td>{$day}</td>";

    foreach ($dayData['cars'] as $car) {
        echo "<td>id: {$car['id']}<br>km: {$car['km']}<br>is broken: {$car['is_broken']}</td>";
    }

    echo "</tr>";

}
echo '</table>';

echo '<h3>JSON отчет</h3>';

// вывод отчета о работе таксопарка в формате json
echo json_encode($report->data);

echo '</body></html>';









