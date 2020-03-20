<?php
session_start();
header('Content-Type:text/plain');

$xml=simplexml_load_string(file_get_contents("https://" . "api.westminster.org.uk/api1/1/auth.asp?username=" . $_GET['username'] . "&password=" . $_GET['password'])) or die("Error recieveing Westminster API response");
if ($xml->authentication != "successful") die("2"); //Auth not successful

$token = $xml->token;
$token_expires = $xml->expires;
$token = (array) $token;

$_SESSION["token"] = $token[0];


die("1");
?>