<?php
error_reporting("E_ALL &amp; ~E_DEPRECATED &amp; ~E_NOTICE");
require_once "../dblogins.php";
require_once "../auth_main.php";


$sql = "SELECT mary_trips.*, locationfrom.name AS fromname, locationfrom.address AS fromaddress, locationto.name AS toname, locationto.address AS toaddress FROM mary_trips LEFT JOIN mary_locations AS locationfrom ON mary_trips.trip_from=locationfrom.id LEFT JOIN mary_locations AS locationto ON mary_trips.trip_to=locationto.id WHERE mary_trips.id='" . mysqli_real_escape_string($conn, $_GET["trip"]) . "'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) $TRIP = $row;
} else die("Trip not found");

$output = '<link href="requiredfiles/bootstrap.css" rel="stylesheet"><style>td { padding: 2px; }</style>';
//$output .= implode(" ",$SHOWSETTINGS);
$output .= '<table border="0" class="table table-striped">
			<thead>
			  <tr>
				<th><th>
				<th style="text-align: center;">Item</th>
				<th style="text-align: center; width: 5px;"></th>
				<th style="text-align: center;">Remarks</th>

			   </tr>
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
			$sql .= "WHERE mary_items.showid='" . $_SESSION['showid'] . "' AND hirebuy != '0' AND niceid IN ('" . str_replace(",","','",$TRIP['items']). "') \n\n ORDER BY mary_importance.rank ASC, hirebuy ASC, mary_items.type ASC, mary_items.niceid ASC";
			$result = $conn->query($sql);
			//die($sql);
			if ($result->num_rows > 0) {
				$counter = 0;
				 while($row = $result->fetch_assoc()) {
					$output .= '<tr>';
					$output .= '<td align="center" style="text-align:center; font-size:150%; font-weight:bold; width: 5px;">&#9744;</td>';
					$output .= '<td style="width: 10px; padding: 5px; background-color: #E0E0E0; text-align: center;">' . str_replace(",","<br/>",$row["niceid"]) . '</td>';
					$output .= '<td style="background-color: ' . ($counter % 2 == 0 ? '#F0F0F0' : '#FFFFFF') . '; width: 170px;">' . ($row["quantity"] > 1 ? '<span style="font-weight: bold;">' . $row["quantity"] . 'x</span> ' : null) . $row["name"] . '</td>';
					$output .= '<td style="background-color: ' . '#E0E0E0' . ';">' . strtoupper($row["typename"]) . '</td>';
					
					$output .= '<td style="background-color: ' . ($counter % 2 == 0 ? '#F0F0F0' : '#FFFFFF') . '; width: 200px;">';
					if ($row["hirebuy"] == 1) $output .= 'Borrowed ' . ($row["ownername"] != null ? ' (<b>Owner:</b>  ' . $row["ownername"] . ')' : null) . '<br/>';
					elseif ($row["hirebuy"] == 2) $output .= 'Bought' .  ($row["pricepaid"] != null ? ' (Paid £' . $row["pricepaid"] . ')' : null) . '<br/>';
					elseif ($row["hirebuy"] == 3) $output .= 'Hired ' . ($row["ownername"] != null ? '(Belongs to ' . $row["ownername"] . ')' : null) . '<br/>';
					//else $output .= 'To be obtained';
					if ($row["personaltoname"] != null) $output .= 'Personal prop to ' . ucwords(strtolower($row["personaltoname"])) . '<br/>';
					
					$output .= ($row["importance"] != "0" ? '<b>Importance:</b> Item is ' . $row["importancename"] : '<b>Importance:</b> Not Assessed') . '<br/>'; //importance
					
					$output .= ($row["location"] != "0" ? '<b>Location:</b> ' . $row["locationname"] . ' (' . $row["locationaddress"] . ')' : '<b>Location:</b> Unknown') . '<br/>'; //Location
					
					
					$output .= '</td>';
			
					$output .= '</tr>';
					$counter++;
				 }
			} else die('Sorry - There are no items in the catagory you selected!');
$output .= '</tbody></table>';


require_once '../libs/mpdf/mpdf.php';
$mpdf=new mPDF('', 'A4');
$mpdf->setAutoTopMargin = 'stretch';
$mpdf->setAutoBottomMargin = 'stretch';

$mpdf->SetHTMLHeader('<table border="0"  style="width: 100%;">
						<tr style="width: 100%;"><td style="width: 100%; text-align: center; font-weight: bold;">' . $SHOW['name'] . ' - WAYBILL - ' . date("d/m/Y", strtotime($TRIP["date"])) . '</td></tr>
					</table><br/>
					<table border="0"  style="width: 100%;">
						<tr style="width: 100%;"><td style="width: 50%; text-align: right; font-weight: bold;">' . $TRIP["fromname"] . '&nbsp;&#8594;<br/>' . $TRIP["fromaddress"] . '&nbsp;&nbsp;&nbsp;</td><td style="width: 50%; text-align: left;">' . $TRIP["toname"] . '<br/>' . $TRIP["toaddress"] . '</td></tr>
					</table> ' . 
					($TRIP["completed"] == "1" ? '<br/>
					<h2 style="width: 100%; text-align: center;">COMPLETED</h2>' : null));
$mpdf->SetHTMLFooter('<table border="0"  style="width: 100%;"><tr style="width: 100%;"><td style="width: 50%;">Generated at ' . date("H:i") . ' on ' . date("d/m/Y") . '. Page {PAGENO}/{nbpg}</td><td align="right" style="width: 50%; font-style: italic;">Mary Asset Manager v' . $version . '</td></tr></table>');
$mpdf->SetTitle($SHOW['name'] . ' Property List');
$mpdf->SetAuthor ($SHOW['name']);
$mpdf->WriteHTML($output);
$mpdf->Output('PropertyList.pdf','I');
?>