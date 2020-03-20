<?php
require_once '../dblogins.php';
require_once '../functions.php';


if (!isset($_GET["trip"])) die("Error - No get values");

$sql = "SELECT items, trip_to FROM mary_trips WHERE id='" . mysqli_real_escape_string($conn, $_GET['trip']) . "'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$items = $row['items'];
		$trip_to = $row['trip_to'];
	}
}

$sql = "UPDATE `mary_items` SET  `location` =  '" . $trip_to . "' WHERE `niceid`  IN ('" . str_replace(",","','",$items) . "')";
mysqli_query($conn, $sql);

$updatesql = "UPDATE `mary_trips` SET completed='1' WHERE id='" . mysqli_real_escape_string($conn, $_GET['trip']) . "'";

mysqli_query($conn, $updatesql);
header('Location: ../');
