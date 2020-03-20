<?php
require_once '../dblogins.php';
require_once '../functions.php';
require_once '../auth_main.php';
if (!isset($_GET["niceid"]) or !$edit) die("Error - You are not logged in!");

if ($_GET["quantity"] > 1) {
	$niceid = "";
	for ($x = 1; $x <= $_GET["quantity"]; $x++) {
		if ($x != 1) $niceid .= ",";
		$niceid .= newniceid();
	} 
} else $niceid = $_GET["niceid"];

$sql = "INSERT INTO `mary_items` (`id`, 
							`niceid`,
							`quantity`,
							`name`,
							`description`,
							`notes`,
							`type`,
							`value`,
							`pricepaid`,
							`hirebuy`,
							`location`,
							`importance`,
							`owner`,
							`personalto`,
							`showid`) 
						VALUES (
							NULL
							, '" . mysqli_real_escape_string($conn, $niceid) . "'
							, '" . mysqli_real_escape_string($conn, $_GET["quantity"]) . "'
							, '" . mysqli_real_escape_string($conn, $_GET["name"]) . "'
							, '" . mysqli_real_escape_string($conn, $_GET["description"]) . "'
							, '" . mysqli_real_escape_string($conn, $_GET["notes"]) . "'
							, '" . mysqli_real_escape_string($conn, $_GET["type"]) . "'
							, '" . mysqli_real_escape_string($conn, $_GET["value"]) . "'
							, '" . mysqli_real_escape_string($conn, $_GET["pricepaid"]) . "'
							, '" . mysqli_real_escape_string($conn, $_GET["hirebuy"]) . "'
							, '" . mysqli_real_escape_string($conn, $_GET["location"]) . "'
							, '" . mysqli_real_escape_string($conn, $_GET["importance"]) . "'
							, '" . mysqli_real_escape_string($conn, $_GET["owner"]) . "'
							, '" . mysqli_real_escape_string($conn, $_GET["personalto"]) . "'
							,'" . $_SESSION['showid'] . "'
						)";
if (!mysqli_query($conn, $sql)) {
	echo("Sorry - An error was encountered!");
} else {
	die("1");
}
?>