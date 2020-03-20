<?php

function phonetic($string)  {
	$nato = array(
	"a" => "alfa", 
	"b" => "bravo", 
	"c" => "charlie", 
	"d" => "delta", 
	"e" => "echo", 
	"f" => "foxtrot", 
	"g" => "golf", 
	"h" => "hotel", 
	"i" => "india", 
	"j" => "juliett", 
	"k" => "kilo", 
	"l" => "lima", 
	"m" => "mike", 
	"n" => "november", 
	"o" => "oscar", 
	"p" => "papa", 
	"q" => "quebec", 
	"r" => "romeo", 
	"s" => "sierra", 
	"t" => "tango", 
	"u" => "uniform", 
	"v" => "victor", 
	"w" => "whisky", 
	"x" => "x-ray", 
	"y" => "yankee", 
	"z" => "zulu", 
	"0" => "zero", 
	"1" => "one", 
	"2" => "two", 
	"3" => "three", 
	"4" => "four", 
	"5" => "five", 
	"6" => "six", 
	"7" => "seven", 
	"8" => "eight", 
	"9" => "niner"
	);
		$output = '';
	for ($i=0; $i<strlen($string); $i++) {
		if (isset($nato[strtolower($string[$i])])) $output .= ' ' . ucfirst($nato[strtolower($string[$i])]);
		else $output .= ' ' . ucfirst($string[$i]);
		
	}
return $output;
}
function ranstring($length = 10) {
	$alphabets = range('A','Z');
	$numbers = range('0','9');
	$final_array = array_merge($alphabets,$numbers);

	$password = '';
	while($length--) {
		$key = array_rand($final_array);
		$password .= $final_array[$key];
	}
	return $password;
}
function newniceid() {
	global $conn;
	//Get all current niceids
	$sql = "SELECT niceid FROM mary_items";
	$result = $conn->query($sql);

	while ($row = $result->fetch_assoc()) {
		foreach (explode(",",$row["niceid"]) as $niceid) $niceids[] = $niceid;
	}
	
	//Get all deleted niceids 
	$sql = "SELECT niceid FROM mary_deleteditems";
	$result = $conn->query($sql);

	while ($row = $result->fetch_assoc()) {
		foreach (explode(",",$row["niceid"]) as $niceid) $niceids[] = $niceid;
	}
	
	$niceid = ranstring(5); //Initial NiceID
	
	while (in_array($niceid, $niceids)) $niceid = ranstring(5); //Prevent Duplicates
	
	return $niceid;
}
?>