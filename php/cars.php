<?php
header("Content-Type:application/json");
header("Access-Control-Allow-Origin: http://localhost:63342");
header("Access-Control-Allow-Methods: GET");
//Access-Control-Allow-Methods: GET
//Access-Control-Allow-Headers: Content-Type, Authorization

$databaze = 'ete89e_1920zs_03';
$uzivatel = 'ete89e_1920zs_03';
$heslo = 'w2LLED';

$mysqli = new mysqli("localhost", $uzivatel, $heslo, $databaze);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

//     "modelType":"Trend",

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
WHERE USER_VEHICLES.ID_US = ?
");
$stmt->bind_param("i", $_GET['userId']);


$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $myArray[] = $row;
}
$stmt->close();
echo json_encode($myArray);

?>



