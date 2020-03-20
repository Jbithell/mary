<?php
require_once '../dblogins.php';
require_once '../functions.php';

if (!isset($_GET["itemid"])) die("Error - No get values");

$sql = "INSERT INTO mary_deleteditems
		SELECT *
		FROM items
		WHERE `id` ='" . $_GET["itemid"] . "'";
if (!mysqli_query($conn, $sql)) {
	//echo mysqli_error($conn) . $sql;
	echo("Sorry - An error was encountered!");
} elseif (!mysqli_query($conn, "DELETE FROM mary_items WHERE `id` ='" . $_GET["itemid"] . "'")) {
	echo("Sorry - An error was encountered!");
} else {
	die("1");
}
?>