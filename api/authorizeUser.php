
<?php

require '../db.php';

session_id($_GET['sessionId']);
session_start();

//echo $_SESSION["username"] ."\n";
$stmt = $cnn->prepare("SELECT ID_US FROM USERS WHERE USERNAME= ? limit 1");
$stmt->bind_param("s", $_SESSION["username"]);
$stmt->execute();
$userId = $stmt->get_result()->fetch_assoc()['ID_US'];
$stmt->close();

if(!isset($userId)) {
    header('HTTP/1.0 401 Unathorized');
    die();
}

?>