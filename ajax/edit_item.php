<?php
require_once '../dblogins.php';
require_once '../functions.php';

if (!isset($_GET["itemid"])) die("Error - No get values");

$sql = "UPDATE  `mary_items` SET  `name` =  '" . mysqli_real_escape_string($conn, $_GET["name"]) . "'
							,`description` =  '" . mysqli_real_escape_string($conn, $_GET["description"]) . "'
							, `notes` =  '" . mysqli_real_escape_string($conn, $_GET["notes"]) . "'
							, `hirebuy` =  '" . mysqli_real_escape_string($conn, $_GET["hirebuy"]) . "'
							, `pricepaid` =  '" . mysqli_real_escape_string($conn, $_GET["pricepaid"]) . "'
							, `value` =  '" . mysqli_real_escape_string($conn, $_GET["value"]) . "'
							, `importance` =  '" . mysqli_real_escape_string($conn, $_GET["importance"]) . "'
							, `personalto` =  '" . mysqli_real_escape_string($conn, $_GET["personalto"]) . "'
							, `owner` =  '" . mysqli_real_escape_string($conn, $_GET["owner"]) . "'
							
							, `quantity` =  '" . mysqli_real_escape_string($conn, $_GET["quantity"]) . "'
							, `location` =  '" . mysqli_real_escape_string($conn, $_GET["location"]) . "'
							
							, `type` =  '" . mysqli_real_escape_string($conn, $_GET["type"]) . "'
			
							WHERE `id` ='" . $_GET["itemid"] . "'";
if (!mysqli_query($conn, $sql)) {
	echo("Sorry - An error was encountered!");
} else {
	die("1");
}
?>