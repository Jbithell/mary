<?php
require_once '../dblogins.php';
require_once '../functions.php';
require_once '../auth_main.php';
header("Content-Type: text/plain");
if (!isset($_GET["from"]) or !$edit) die(0);

$sql = "INSERT INTO `mary_trips` (`id` ,
							`items` ,
							`showid` ,
							`trip_from` ,
							`trip_to` ,
							`date` ,
							`completed`) 
						VALUES (
							NULL
							, '" . mysqli_real_escape_string($conn, $_GET["items"]) . "'
							, '" . mysqli_real_escape_string($conn, $_SESSION['showid']) . "'
							, '" . mysqli_real_escape_string($conn, $_GET["from"]) . "'
							, '" . mysqli_real_escape_string($conn, $_GET["to"]) . "'
							, '" . mysqli_real_escape_string($conn, date("Y-m-d", strtotime($_GET["date"]))) . "'
							, '0'
						)";
$result = mysqli_query($conn, $sql);