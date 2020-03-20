<?php
require_once 'dblogins.php';
if (isset($_SESSION['showid'])) echo '';//Do nothing
elseif (isset($_GET['showid'])) $_SESSION['showid'] = $_GET['showid'];
else {
	header("Location: //jbithell.com/projects/mary/showselector.php");
	exit;
}


$sql = "SELECT * FROM mary_shows WHERE id='" . $_SESSION['showid'] . "'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		 $SHOW['name'] = $row['name'];
	 }
} else die('Sorry - Show not found!');

/*function tokencheck($token) {
	$xml=simplexml_load_string(file_get_contents("https://" . "api.westminster.org.uk/api1/1/token.asp?token=" . $token)) or die("Error recieveing Westminster API response");
	if ($xml->status != "valid") return false;
	else return true; //Westminster Token must be ok!
} function user($token) {
	global $_SESSION;
	$xml=simplexml_load_string(file_get_contents("https://" . "api.westminster.org.uk/api1/1/profile.asp?token=" . $token)) or die("Error recieveing Westminster API response");
	$xml = (array) $xml;
	if (isset($xml[0]) and $xml[0] == "token invalid or expired") die('Please login again');
	
	global $conn;
	$result = $conn->query("SELECT * FROM ws_users WHERE uwi='" . $xml['uwi'] . "' AND showid='" . $_SESSION['showid'] . "'");
	
	if ($result->num_rows == 1) {
		while($row = $result->fetch_assoc()) {
			if ($row['edit'] == "1") $user['edit'] = true;
			else $user['edit'] = false;
			if ($row['admin'] == "1") $user['admin'] = true;
			else $user['admin'] = false;
			
			$user['name'] = $xml['preferredname'];
			$user['surname'] = $xml['surname'];
			$user['edit'] = true;
			
			return $user;
		
		}
	} elseif ($result->num_rows == 0) {
		$sql = "INSERT INTO `ws_users` (`id`, `uwi`, `name`, `surname`, `type`, `emailaddress`, `housename`, `yearname`, `admin`, `edit`,   `showid`) VALUES 
														(NULL, '" . $xml['uwi'] . "', '" . $xml['preferredname'] . "', '" . $xml['surname'] . "', '" . $xml['type'] . "', '" . $xml['emailaddress'] . "', " . (isset($xml['housename']) ? "'" . mysqli_real_escape_string($conn,$xml['housename']) . "'" : "NULL") . ", " . (isset($xml['yearname']) ? "'" . $xml['yearname'] . "'" : "NULL") . ", '0', '1', '" . $_SESSION['showid'] . "')";
		//die($sql);
		$result = $conn->query($sql);
		if (!$result) die('Error - Please try again later');
		
		$user['name'] = $xml['preferredname'];
		$user['surname'] = $xml['surname'];
		$user['edit'] = true;
		
		
		return $user;
	} else die('Error - You may have a duplicate account - Please contact support');

}
if (isset($_SESSION['token'])) {
	if (tokencheck($_SESSION['token'])) {
		$user = user($_SESSION['token']);
		if ($user['edit']) $edit = true;
		else $edit = false;
	}
	else $edit = false;
} else $edit = false;
*/
$edit = true;
if (isset($_GET["logout"])) { //Logout System
	$_SESSION = array(); //Logout
	header("Location: ?");
	die();
}


?>