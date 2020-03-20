<?php
error_reporting("E_ALL &amp; ~E_DEPRECATED &amp; ~E_NOTICE");
require_once "../dblogins.php";
require_once "../auth_main.php";




//SETTINGS
//Display settings stored in array (default values below) - These values can be changed by using get.
$SHOWSETTINGS = array("description" => true, "pricepaid" => true, "value" => true, "importance" => true, "location" => true, "remarks" => true, "niceid" => true); //Default Values
foreach($_GET as $key => $value) {
	if (isset($SHOWSETTINGS[$key])) {
		if ($value == 1 or $value == "true") $SHOWSETTINGS[$key] = true;
		elseif ($value == 0 or $value == "false") $SHOWSETTINGS[$key] = false;
	}
}
if (isset($_GET["type"])) $SHOWSETTINGS["type"] = mysqli_real_escape_string($conn, $_GET["type"]);
//END SETTINGS


$output = '<link href="requiredfiles/bootstrap.css" rel="stylesheet"><style>td { padding: 2px; }</style>';
//$output .= implode(" ",$SHOWSETTINGS);
$output .= '<table border="0" class="table table-striped">
			<thead>
			  <tr>'
			  . ($SHOWSETTINGS["niceid"] ? '<th><th>' : null)
				. '<th style="text-align: center;">Item</th>'
				. ((!isset($SHOWSETTINGS["type"]) or (strpos($SHOWSETTINGS["type"],',') !== false)) ? '<th style="text-align: center; width: 5px;"></th>' : null)
				. ($SHOWSETTINGS["description"] ? '<th>Description</th>' : null)
				. ($SHOWSETTINGS["remarks"] ? '<th style="text-align: center;">Remarks</th>' : null)
			   . '</tr>
			</thead>
			<tbody>';
			$sql = "SELECT mary_items.*, mary_cast.name AS personaltoname, mary_owners.name AS ownername, mary_locations.name AS locationname, mary_locations.address AS locationaddress, mary_importance.name AS importancename, mary_types.name AS typename FROM mary_items 
					LEFT JOIN mary_cast
					ON mary_items.personalto = mary_cast.id 
					LEFT JOIN mary_owners
					ON mary_items.owner = mary_owners.id
					LEFT JOIN mary_locations
					ON mary_items.location = mary_locations.id
					LEFT JOIN mary_types
					ON mary_items.type = mary_types.id
					LEFT JOIN mary_importance
					ON mary_items.importance = mary_importance.id\n\n";
			if (isset($SHOWSETTINGS["type"])) $sql .= "WHERE mary_items.type IN(" . $SHOWSETTINGS["type"] . ") AND "; //Filter by type
			else $sql .= "WHERE ";
			$sql .= "\n mary_items.showid='" . $_SESSION['showid'] . "' \n\n ORDER BY mary_importance.rank ASC, mary_items.hirebuy ASC, mary_items.type ASC, mary_items.niceid ASC";
			$result = $conn->query($sql);
			echo mysqli_error($conn);
							

			if ($result->num_rows > 0) {
				$counter = 0;
				 while($row = $result->fetch_assoc()) {
					$output .= '<tr>';
					$output .= '<td align="center" style="text-align:center; font-size:150%; font-weight:bold; width: 5px;">' . ($row["hirebuy"] == 0 ? '&#9744;' : '&#9745;') . '</td>';
					if ($SHOWSETTINGS["niceid"]) $output .= '<td style="width: 10px; padding: 5px; background-color: #E0E0E0; text-align: center;">' . str_replace(",","<br/>",$row["niceid"]) . '</td>';
					$output .= '<td style="background-color: ' . ($counter % 2 == 0 ? '#F0F0F0' : '#FFFFFF') . '; width: 170px;">' . ($row["quantity"] > 1 ? '<span style="font-weight: bold;">' . $row["quantity"] . 'x</span> ' : null) . $row["name"] . '</td>';
					
					if (!isset($SHOWSETTINGS["type"]) or (strpos($SHOWSETTINGS["type"],',') !== false)) $output .= '<td style="background-color: ' . '#E0E0E0' . ';">' . strtoupper($row["typename"]) . '</td>';
					
					if ($SHOWSETTINGS["description"]) $output .= '<td style="background-color: ' . ($counter % 2 == 0 ? '#F0F0F0' : '#FFFFFF') . ';">' . $row["description"] . '</td>';
					
					if ($SHOWSETTINGS["remarks"]) {
						$output .= '<td style="background-color: ' . ($counter % 2 == 0 ? '#F0F0F0' : '#FFFFFF') . '; width: 200px;">';
						if ($row["hirebuy"] == 1) $output .= 'Borrowed ' . ($row["ownername"] != null ? ' (<b>Owner:</b>  ' . $row["ownername"] . ')' : null) . ' ' . ($SHOWSETTINGS["pricepaid"] ? ($row["pricepaid"] != null ? '(£' . $row["value"] . ' was paid for this item)' : '(<b>Price Paid:</b> Not recorded)') : null) . '<br/>';
						elseif ($row["hirebuy"] == 2) $output .= 'Bought' .  ($row["pricepaid"] != null ? ' (Paid £' . $row["pricepaid"] . ')' : null) . '<br/>';
						elseif ($row["hirebuy"] == 3) $output .= 'Hired ' . ($row["ownername"] != null ? '(Belongs to ' . $row["ownername"] . ')' : null) . '<br/>';
						//else $output .= 'To be obtained';
						if ($row["personaltoname"] != null) $output .= 'Personal prop to ' . ucwords(strtolower($row["personaltoname"])) . '<br/>';
						
						if ($SHOWSETTINGS["value"]) $output .= ($row["value"] != null ? '<b>Value:</b>  ' . $row["value"] : '<b>Value:</b> Unknown') . '<br/>'; //Value
						
						if ($SHOWSETTINGS["importance"]) $output .= ($row["importance"] != "0" ? '<b>Importance:</b> Item is ' . $row["importancename"] : '<b>Importance:</b> Not Assessed') . '<br/>'; //importance
						
						if ($SHOWSETTINGS["location"]) $output .= ($row["location"] != "0" ? '<b>Location:</b> ' . $row["locationname"] . ' (' . $row["locationaddress"] . ')' : '<b>Location:</b> Unknown') . '<br/>'; //Location
						
						
						$output .= '</td>';
					}
					$output .= '</tr>';
					$counter++;
				 }
			} else die('Sorry - There are no items in the catagory you selected!');
$output .= '</tbody></table>';

//die($output);

require_once '../libs/mpdf/mpdf.php';
$mpdf=new mPDF('', 'A4');
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->setAutoBottomMargin = 'stretch';
if (isset($SHOWSETTINGS["type"])) {
	$types_result = mysqli_query($conn, "SELECT plural FROM mary_types WHERE id IN(" . $SHOWSETTINGS["type"] . ")");
	if (mysqli_num_rows($types_result) > 1) {
		$counter = 0; 
		$title = '';
		while ($row = $types_result->fetch_assoc()) {
			if ($counter != 0 and ($counter +1) != mysqli_num_rows($types_result)) $title .= ', ';
			elseif (($counter +1) == mysqli_num_rows($types_result)) $title .= ' &amp; ';
			
			$title .= $row["plural"];
			$counter++;
		}
		$title .= ' List';
	} else {
		$title = mysqli_fetch_row($types_result)[0] . ' List';
	}
} else {
	$title = 'Property List';
}
$mpdf->SetHTMLHeader('<table border="0"  style="width: 100%;"><tr style="width: 100%;"><td style="width: 100%; text-align: center; font-weight: bold;">' . $SHOW['name'] . ' - ' . $title . '</td></tr></table>');
$mpdf->SetHTMLFooter('<table border="0"  style="width: 100%;"><tr style="width: 100%;"><td style="width: 50%;">Generated at ' . date("H:i") . ' on ' . date("d/m/Y") . '. Page {PAGENO}/{nbpg}</td><td align="right" style="width: 50%; font-style: italic;">Mary Asset Manager v' . $version . '</td></tr></table>');
$mpdf->SetTitle($SHOW['name'] . ' Property List');
$mpdf->SetAuthor ($SHOW['name']);
$mpdf->WriteHTML($output);
$mpdf->Output('PropertyList.pdf','I');
?>