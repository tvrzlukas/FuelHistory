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

$stmt = $mysqli->prepare("SELECT 
COUNT(*) as refuelsCount,
SUM(LITERS*LITER_PRICE) AS totalCost,
AVG(LITER_PRICE) as avgLiterPrice
FROM 
FUEL_HIST
WHERE ID_UV = ?");
$stmt->bind_param("i", $_GET['carId']);


$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $myArray[] = $row;
}
$stmt->close();
echo json_encode($myArray);

//<!--year(curdate())-->
//<!--SELECT id FROM things-->
//<!--WHERE MONTH(curdate()) = 1 AND YEAR(happened_at) = 2009-->

?>

