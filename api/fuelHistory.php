<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require('./authorizeUser.php');


switch (strtolower($_SERVER['REQUEST_METHOD'])) {

    case "put":
        parse_str(file_get_contents("php://input"), $vars);
        $stmt = $cnn->prepare("INSERT INTO
            FUEL_HIST(ID_UV, MILEAGE, LITERS, LITER_PRICE, FUEL_DATE)
             (
                SELECT ?, ?, ?, ?, STR_TO_DATE(?, '%Y-%m-%d') 
                FROM
                    DUAL
                WHERE 
                    ? IN (
                            SELECT 
                                uv.ID_UV 
                            FROM 
                                USER_VEHICLES uv 
                            JOIN USERS us ON uv.ID_US=us.ID_US
                                WHERE us.ID_US=?
                            )
                    )
		");
//        if(!$stmt) {
//            echo 'prepare() failed: ' . htmlspecialchars($cnn->error);
//        }
        $stmt->bind_param("iiddsii", $vars['carId'], $vars['mileage'], $vars['liters'], $vars['literPrice'], $vars['date'], $vars['carId'], $userId);
        $stmt->execute();
        $stmt->close();
        break;

    case "delete":
        parse_str(file_get_contents("php://input"), $vars);
        $stmt = $cnn->prepare("DELETE FROM FUEL_HIST 
            WHERE ID_FH = ? 
            AND ID_UV IN (
                SELECT 
                    uv.ID_UV 
                FROM 
                    USER_VEHICLES uv 
                    JOIN USERS us ON uv.ID_US=us.ID_US
                WHERE us.ID_US=?
	    )");
        $stmt->bind_param("ii", $vars['fhId'], $userId);
        $exec = $stmt->execute();
        $stmt->close();
        $cnn->close();
        break;

    case "get":
        $stmt = $cnn->prepare("SELECT
                fh.ID_FH as fhId,
                fh.FUEL_DATE as date,
                ROUND(fh.MILEAGE, 2) as mileage,
                ROUND(fh.LITERS, 2) as units,
                ROUND(fh.LITER_PRICE, 2) as priceForOneUnit,
                ROUND((fh.LITER_PRICE * LITERS), 2)  as totalPrice
            FROM 
            FUEL_HIST fh
            JOIN USER_VEHICLES uv ON fh.ID_UV=uv.ID_UV
            JOIN USERS us ON uv.ID_US=us.ID_US
            WHERE 
                uv.ID_UV = ?
                AND us.ID_US=?
            ORDER BY FUEL_DATE DESC");
        $stmt->bind_param("ii", $_GET['carId'], $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $myArray[] = $row;
        }
        $stmt->close();

        if (isset($myArray)) {
            echo json_encode($myArray);
        } else {
            echo "[]";
        }

        break;

    default:
        header('HTTP/1.0 501 Not Implemented');
        die();
}


?>

