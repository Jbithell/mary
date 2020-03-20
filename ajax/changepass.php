<?php
require_once '../dblogins.php';
require_once '../functions.php';

if (!isset($_GET["id"])) die("Error - No get values");
$sql = "SELECT * FROM mary_users WHERE id ='" . mysqli_real_escape_string($conn, $_GET["id"]) . "' AND `pass` ='" . hash('sha256', mysqli_real_escape_string($conn, $_GET["pass"])) . "'";
$result = $conn->query($sql);


if ($result->num_rows != 1) die("2");


$sql = "UPDATE  `mary_users` SET  `pass` =  '" . hash('sha256', mysqli_real_escape_string($conn, $_GET["newpass"])) . "' WHERE `id` ='" . mysqli_real_escape_string($conn, $_GET["id"]) . "' AND `pass` ='" . hash('sha256', mysqli_real_escape_string($conn, $_GET["pass"])) . "'";
if (!mysqli_query($conn, $sql)) {
	echo("Sorry - An unknown error was encountered");
} else {
	die("1");
}
?>