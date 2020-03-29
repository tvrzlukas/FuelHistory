<?php
//header("Content-Type:application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, DELETE, GET");
header("Access-Control-Allow-Headers: Origin, Methods, Content-Type");

//Access-Control-Allow-Headers: Content-Type, Authorization

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$databaze = 'ete89e_1920zs_03';
$uzivatel = 'ete89e_1920zs_03';
$heslo = 'w2LLED';

$mysqli = new mysqli("localhost", $uzivatel, $heslo, $databaze);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

switch (strtolower($_SERVER['REQUEST_METHOD'])) {

    case "put":
        parse_str(file_get_contents("php://input"), $vars);
        echo $vars['carId'] . ' ' . $vars['mileage'] . ' ' . $vars['liters'] . ' ' . $vars['literPrice'] . ' ' . $vars['date'];
        $stmt = $mysqli->prepare("INSERT INTO
                FUEL_HIST(ID_UV, MILEAGE, LITERS, LITER_PRICE, FUEL_DATE)
                VALUES (?, ?, ?, ?, STR_TO_DATE(?, '%Y-%m-%d'))");
        $stmt->bind_param("iidds", $vars['carId'], $vars['mileage'], $vars['liters'], $vars['literPrice'], $vars['date']);
        $stmt->execute();
        $stmt->close();
        break;

    case "delete":
        echo "this is a delete request\n";
        parse_str(file_get_contents("php://input"), $vars);
        $stmt = $mysqli->prepare("DELETE FROM FUEL_HIST WHERE ID_FH = ?");
        echo $vars['fhId'];
        $stmt->bind_param("i", $vars['fhId']);
        $exec = $stmt->execute();
        if (false === $exec) {
            error_log('mysqli execute() failed: ');
            error_log(print_r(htmlspecialchars($stmt->error), true));
        }
        $stmt->close();
        $mysqli->close();
        break;

    case "get":
        $stmt = $mysqli->prepare("SELECT
            ID_FH as fhId,
            FUEL_DATE as date,
            ROUND(MILEAGE, 2) as mileage,
            ROUND(LITERS, 2) as units,
            ROUND(LITER_PRICE, 2) as priceForOneUnit,
            ROUND((LITER_PRICE * LITERS), 2)  as totalPrice
            FROM FUEL_HIST
            WHERE ID_UV = ?
            ORDER BY FUEL_DATE DESC");
        $stmt->bind_param("i", $_GET['carId']);
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

