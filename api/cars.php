<?php
header("Content-Type:application/json");
header("Access-Control-Allow-Methods: GET");

$databaze = 'ete89e_1920zs_03';
$uzivatel = 'ete89e_1920zs_03';
$heslo = 'w2LLED';

$mysqli = new mysqli("localhost", $uzivatel, $heslo, $databaze);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

session_id($_GET['sessionId']);
session_start();;

//todo by user
$stmt = $mysqli->prepare("SELECT
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
WHERE USERS.USERNAME = ?
");
$stmt->bind_param("i", $_SESSION["username"]);


$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $myArray[] = $row;
}
$stmt->close();
echo json_encode($myArray);

?>



