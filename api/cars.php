<?php


require('./authorizeUser.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

switch (strtolower($_SERVER['REQUEST_METHOD'])) {

    case "post":
        parse_str(file_get_contents("php://input"), $vars);
//        echo $vars['manufacturer'] . ' ' . $vars['mileage'] . ' ' . $vars['liters'] . ' ' . $vars['literPrice'] . ' ' . $vars['date'];
        $stmt = $cnn->prepare("INSERT INTO
            USER_VEHICLES(ID_US, LIC_PLATE, ID_MO, MAN_YEAR, ID_FT, CUBICS, POWER, REGISTRATION_MILEAGE)
                VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("isiiisii", $userId ,$vars['spz'], $vars['model'],  $vars['year'], $vars['fuelType'], $vars['cubics'], $vars['power'], $vars['registrationMileage']);
        $stmt->execute();
        $stmt->close();
        break;

    case "put":
        parse_str(file_get_contents("php://input"), $vars);
//        echo $vars['manufacturer'] . ' ' . $vars['mileage'] . ' ' . $vars['liters'] . ' ' . $vars['literPrice'] . ' ' . $vars['date'];
        $stmt = $cnn->prepare("UPDATE USER_VEHICLES SET
             LIC_PLATE = ?,
             MAN_YEAR = ?,
             CUBICS = ?,
             POWER = ?,
             REGISTRATION_MILEAGE = ?
        WHERE ID_UV = ? AND ID_US = ?");
        $stmt->bind_param("sisiiii",$vars['spz'],  $vars['year'], $vars['cubics'], $vars['power'], $vars['registrationMileage'], $vars['carId'], $userId );
//        echo $vars['spz'], $vars['model'], $vars['year'], $vars['fuelType'], $vars['cubics'], $vars['power'], $vars['registrationMileage'], $vars['carId'], $userId;
        $stmt->execute();
        echo "$stmt->affected_rows";
        $stmt->close();
        break;

    case "delete":
        parse_str(file_get_contents("php://input"), $vars);
        $stmt = $cnn->prepare("DELETE FROM USER_VEHICLES WHERE ID_UV = ? AND ID_US = ?");
        $stmt->bind_param("ii", $vars['carId'], $userId);
        $exec = $stmt->execute();
        if (false === $exec) {
            error_log('mysqli execute() failed: ');
            error_log(print_r(htmlspecialchars($stmt->error), true));
        }
        $stmt->close();
        break;

    case "get":
        $stmt = $cnn->prepare("SELECT
                USER_VEHICLES.ID_UV as carId,
                MANUFACTURER.NAME as manufacturer,
                MODEL.NAME as model,
                USER_VEHICLES.MAN_YEAR as 'year',
                USER_VEHICLES.LIC_PLATE as licencePlate,
                USER_VEHICLES.REGISTRATION_MILEAGE as registrationMileage,
                CONCAT(USER_VEHICLES.CUBICS, 'l ', USER_VEHICLES.POWER, ' kw') as engine, 
                FUEL_TYPE.TYPE as fuelType
            FROM USER_VEHICLES
            INNER JOIN MODEL
            ON USER_VEHICLES.ID_MO = MODEL.ID_MO
            INNER JOIN MANUFACTURER
            ON MODEL.ID_MA = MANUFACTURER.ID_MA
            INNER JOIN FUEL_TYPE
            ON USER_VEHICLES.ID_FT = FUEL_TYPE.ID_FT
            INNER JOIN USERS
            ON USER_VEHICLES.ID_US = USERS.ID_US
            WHERE USERS.ID_US = ?
            ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $myArray = [];
        while ($row = $result->fetch_assoc()) {
            $myArray[] = $row;
        }
        $stmt->close();
        echo json_encode($myArray);
        break;

    default:
        header('HTTP/1.0 501 Not Implemented');
        die();
}

?>



