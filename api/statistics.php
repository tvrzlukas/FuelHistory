<?php



require('./authorizeUser.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class CarStatistics {
    var $totalRefuelCount;
    var $totalCost;
    var $totalDriven;
    var $averageConsumption;
    var $averagePrice;
    var $monthlyCosts;
    var $yearlyDriven;
    var $yearlyRefuelCount;
}

switch (strtolower($_SERVER['REQUEST_METHOD'])) {

    case "get":
        $stmt = $cnn->prepare("
        SELECT 
                COUNT(fuel_date) as totalRefuelCount,
                ROUND(SUM(fh.liters * fh.liter_price),2) as totalCost,
                ROUND(MAX(fh.MILEAGE) - veh.REGISTRATION_MILEAGE,2) as totalDriven,
                ROUND((SUM(fh.LITERS) / (MAX(fh.MILEAGE) - veh.REGISTRATION_MILEAGE))*100, 2) as averageConsumption,
                ROUND(AVG(LITER_PRICE), 2) as averagePrice
            FROM FUEL_HIST as fh
            JOIN USER_VEHICLES as veh ON veh.id_uv = fh.id_uv
            JOIN USERS as u ON u.id_us = veh.id_us 
            WHERE
            veh.id_uv = ? AND u.id_us = ?
            ");
        $stmt->bind_param("ii", $_GET['carId'], $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $carStatistics = new CarStatistics();

        $carStatistics->totalRefuelCount = $result['totalRefuelCount'];
        $carStatistics->totalCost = $result['totalCost'];
        $carStatistics->totalDriven = $result['totalDriven'];
        $carStatistics->averageConsumption = $result['averageConsumption'];
        $carStatistics->averagePrice = $result['averagePrice'];
        $stmt->close();

        $stmt = $cnn->prepare("
            SELECT 
                ROUND(SUM(fh.liters * fh.liter_price), 2) as monthlyCosts
            FROM 
                FUEL_HIST as fh
                JOIN USER_VEHICLES as uv ON uv.id_uv = fh.id_uv
                JOIN USERS as u ON u.id_us = uv.id_us
            WHERE 
                u.id_us = ?
                and uv.id_uv = ?
                and fh.fuel_date > STR_TO_DATE(sysdate(), '%Y-%m')
                    ");
        $stmt->bind_param("ii", $userId, $_GET['carId']);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $carStatistics->monthlyCosts = $result['monthlyCosts'];
        $stmt->close();


        $stmt = $cnn->prepare("
        SELECT 
            ROUND(MAX(fh.MILEAGE) - (CASE 
                WHEN MIN(fh.MILEAGE) = MAX(fh.MILEAGE) THEN uv.REGISTRATION_MILEAGE
             ELSE MIN(fh.MILEAGE) END), 2) as yearlyDriven
        FROM
            FUEL_HIST fh
            JOIN USER_VEHICLES uv ON fh.ID_UV=uv.ID_UV
            JOIN USERS as u ON u.id_us = uv.id_us
        WHERE
            u.id_us = ?
            and uv.id_uv = ?
            and fh.fuel_date > STR_TO_DATE(sysdate(), '%Y')
                    ");
        $stmt->bind_param("ii", $userId, $_GET['carId']);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $carStatistics->yearlyDriven = $result['yearlyDriven'];
        $stmt->close();

        $stmt = $cnn->prepare("
                SELECT COUNT(fuel_date) as yearlyRefuelCount
                FROM FUEL_HIST as fh 
            JOIN USER_VEHICLES as veh ON veh.id_uv = fh.id_uv
            JOIN USERS as u ON u.id_us = veh.id_us
            WHERE u.id_us = ? AND
            veh.id_uv = ? AND
        YEAR(fh.fuel_date) >= YEAR(curdate())
        ");
        $stmt->bind_param("ii", $userId, $_GET['carId']);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $carStatistics->yearlyRefuelCount = $result['yearlyRefuelCount'];
        $stmt->close();

        header("Content-Type:application/json");
        header("Access-Control-Allow-Methods: GET");
        echo json_encode($carStatistics);
        break;

    default:
        header('HTTP/1.0 501 Not Implemented');
        die();
}

?>


