<?php
require_once 'dblogins.php';
require_once 'functions.php';
require_once 'auth_main.php';
newniceid();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Mary - <?=$SHOW['name']?> - Company Asset Tracker</title>
		<meta name="generator" content="Bithell" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="style.css" rel="stylesheet">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
		<script src="theme_main.js"></script>
	</head>
	<body>
<div class="wrapper">
	<div class="box">
		<div class="row row-offcanvas row-offcanvas-left">
			<div class="column col-sm-2 col-xs-1 sidebar-offcanvas" id="sidebar">
				<ul class="nav">
						<li><a href="#" data-toggle="offcanvas" class="visible-xs text-center"><i class="glyphicon glyphicon-chevron-right"></i></a></li>
				</ul>
				 
				<ul class="nav hidden-xs" id="lg-menu">
					<form class="input-group input-group-sm" method="GET" style="width: 95%;">
						<input type="text" class="form-control"  style="width:100%;" placeholder="Search" name="q" id="search">
						<div class="input-group-btn">
						<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
						</div>
					</form>
					<?php
					if ($edit) echo '<li><a href="#additem-modal" role="button" data-toggle="modal"><i class="fa fa-plus"></i> Add Item</a></li>';
					if ($edit) echo '<li><a href="#addtrip-modal" role="button" data-toggle="modal"><i class="fa fa-plus"></i> Add Waybill</a></li>';
					$types_result = mysqli_query($conn, "SELECT * FROM mary_types WHERE showid='" . $_SESSION['showid'] . "'");
					while ($row = $types_result->fetch_assoc()) {
						if ($row["id"] == 0) continue;
						$types[] = $row; //Make into array
					}
					foreach ($types as $type) {
						echo '<li><a href="#list' . strtolower($type["name"]) . '">' . $type["plural"] . '</a></li>';
					}
					?>
					<li><a href="#listtypeall-modal" data-toggle="modal" ><i class="fa fa-file-pdf-o"></i> PDF Property List</a></li>
					<li><a href="#castlist"><i class="fa fa-users"></i> Cast</a></li>
					<li><a href="#support-modal" data-toggle="modal" ><i class="fa fa-life-ring"></i> Help &amp; Support</a></li>
					<li><a href="."><i class="glyphicon glyphicon-refresh"></i> Refresh</a></li>
				</ul>
				<ul class="list-unstyled hidden-xs" id="sidebar-footer">
					<li>
						<h3><?=$SHOW['name']?></h3>
						<h2>Mary</h2>
						<h4>Web Asset Manager</h4>
						James Bithell <i>(&copy;2015)</i>
					</li>
				</ul>
				<ul class="nav visible-xs" id="xs-menu">
					<li><a href="littlepages/support.php" class="text-center"><i class="fa fa-life-ring"></i></a></li>
				</ul>
				
			</div>
			<div class="column" id="main">
				<div class="padding">
					<div class="row">
						
						<?php
						if (isset($_GET["updatesuccess"])) echo '<div class="alert alert-success">
																	  <strong>Success!</strong> Item record has been updated!
																	</div>';
						elseif (isset($_GET["addsuccess"])) echo '<div class="alert alert-success">
																	  <strong>Success!</strong> Item Added!
																	</div>';
						elseif (isset($_GET["deletesuccess"])) echo '<div class="alert alert-danger">
																	  <strong>Success!</strong> Item Deleted!
																	</div>';
						elseif (isset($passfail) and $passfail) echo '<div class="alert alert-danger">
																	  <strong>Password Incorrect</strong> Please check your password was correct
																	</div>';
						elseif (isset($_GET["changepasssuccess"])) echo '<div class="alert alert-success">
																	  <strong>Success!</strong> Password Changed!
																	</div>';
						/*if (!isset($user)) {
							echo '<div class="col-lg-12">
								<div class="well"> 
									<form class="form" method="POST">
										<h4>Sign In</h4>
										<p>You don\'t need to be signed in to view and export the property list to a PDF but to edit the property list you must be signed in.<br/> Contact support to request your password.</p> 
										<p><i>Sign In has now moved to Westminster Accounts - Login using your Westminster School Username and Password - Not your old Mary login details. (Apologies if you were sent a password previously - Please ignore this and use your Westminster account from now on)</i></p>
										<p><a href="westminsterlogin/"><button type="button" class="btn btn-default">Login</button></a></p>
									</form>
								</div>
							</div>';
						} else {
							echo '<div class="alert alert-info">
									  <strong>Hi</strong> ' . $user["name"] . ' ' . $user["surname"] . '&nbsp;&nbsp;<a href="?logout">Logout</a><!--&nbsp;&nbsp;<a href="#changepass-modal" data-toggle="modal">Change Password</a>-->
									</div>';
						}*/
						foreach ($types as $type) {
						?>
							
							<div class="col-lg-12">
								<div class="panel panel-default"  id="list<?=strtolower($type["name"])?>">
									<div class="panel-heading"><a href="#listtype<?=strtolower($type["name"])?>-modal" data-toggle="modal" class="pull-right">PDF Download</a><h4><?=$type["plural"]?></h4></div>
									<div class="panel-body">
										<table class="table table-striped">
											<thead>
											  <tr>
												<th>#</th>
												<th>Item</th>
												<th class="hidden-sm hidden-xs">Description</th>
												<th class="hidden-xs">Remarks</th>
												<th></th>
												<?=($edit ? '<th></th>' : null)?>
											  </tr>
											</thead>
											<tbody>
											<?php
											$sql = "SELECT mary_items.*, mary_cast.name AS personaltoname, mary_owners.name AS ownername, mary_locations.name AS locationname, mary_locations.address AS locationaddress, mary_types.name AS typename FROM mary_items 
		
											LEFT JOIN mary_cast
											ON mary_items.personalto = mary_cast.id 
											LEFT JOIN mary_owners
											ON mary_items.owner = mary_owners.id
											LEFT JOIN mary_locations
											ON mary_items.location = mary_locations.id
											LEFT JOIN mary_types
											ON mary_items.type = mary_types.id
					
											WHERE mary_items.showid='" . $_SESSION['showid'] . "' AND mary_items.type=" . $type["id"];
											$result = $conn->query($sql);
											if ($result->num_rows > 0) {
												 while($row = $result->fetch_assoc()) {
												 	echo '<tr>';
													echo '<td>' . str_replace(",","<br/>",$row["niceid"]) . '</td><td>' . ($row["quantity"] > 1 ? '<span class="badge">' . $row["quantity"] . 'x</span>' : null) . $row["name"] . '</td><td class="hidden-sm hidden-xs">' . $row["description"] . '</td>';
													echo '<td  class="hidden-xs">';
													if ($row["hirebuy"] == 1) echo '<button class="btn btn-info" type="button">Loaned</button>';
													elseif ($row["hirebuy"] == 2) echo '<button class="btn btn-warning" type="button">Bought</button>';
													elseif ($row["hirebuy"] == 3) echo '<button class="btn btn-danger" type="button">Hired</button>';
													//else echo '<button class="btn btn-default" type="button">TBO</button>';
													echo '<br/>';
													if ($row["personaltoname"] != null) echo '<a href="#castmember' . $row["personalto"] . '-modal" data-toggle="modal"><button class="btn btn-info" type="button">' . ucwords(strtolower($row["personaltoname"])) . '</button></a>';
													if ($row["ownername"] != null) echo 'Belongs to ' . $row["ownername"];
													echo '</td>';
													echo '<td><a href="#item' . $row["id"] . '-modal" data-toggle="modal"><button class="btn btn-default" type="button"><i class="fa fa-info"></i></button></a></td>';
													
													if ($edit) echo '<td><a href="#item' . $row["id"] . '-edit-modal" data-toggle="modal"><button class="btn btn-default" type="button"><i class="fa fa-pencil"></i></button></a></td>';
										
													echo '</tr>';
						
												 }
											}
											?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<?php
							}
							?>
							<div class="col-lg-6" id="castlist">
								<div class="panel panel-default">
									<div class="panel-heading"><!--<a href="#" class="pull-right">View all</a>--><h4>Cast Members</h4></div>
									<div class="panel-body">
										<div class="list-group">
											<?php
											$sql = "SELECT * FROM mary_cast WHERE showid='" . $_SESSION['showid'] . "'";
											$result = $conn->query($sql);

											if ($result->num_rows > 0) {
												 while($row = $result->fetch_assoc()) {
													 echo '<a href="#castmember' . $row["id"] . '-modal" data-toggle="modal" class="list-group-item">' . ucwords(strtolower($row["name"])) . '</a>';
												 }
											}
											?>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6" id="triplist">
								<div class="panel panel-default">
									<div class="panel-heading"><a onclick="$('.completedtrips').show();" class="pull-right">View Completed</a><h4>Waybills</h4></div>
									<div class="panel-body">
										<div class="list-group">
											<?php
											$tripsql = "SELECT mary_trips.*, locationfrom.name AS fromname, locationfrom.address AS fromaddress, locationto.name AS toname, locationto.address AS toaddress FROM mary_trips LEFT JOIN mary_locations AS locationfrom ON mary_trips.trip_from=locationfrom.id LEFT JOIN mary_locations AS locationto ON mary_trips.trip_to=locationto.id WHERE mary_trips.showid='" . $_SESSION['showid'] . "'";
											$result = $conn->query($tripsql);
											
											if ($result->num_rows > 0) {
												 while($row = $result->fetch_assoc()) {
													 echo '<div class="list-group-item ' . ($row["completed"] == "1" ? 'completedtrips' : null) . '" ' . ($row["completed"] == "1" ? 'style="display: none;"' : null) . '><a href="pdf/waybill.php?trip=' . $row["id"] . '">' . $row["fromname"] . ' (' . $row["fromaddress"] . ') -> ' . $row["toname"] . ' (' . $row["toaddress"] . ') <br/>' . date("d/M/Y", strtotime($row["date"])) . '</a>' . ($row["completed"] != "1" ? ($edit ? '<br/><a href="ajax/complete.php?trip=' . $row["id"] . '"><button class="btn btn-default" type="button">Complete</button></a>' : null) : '<br/><b>Completed</b>') . '</div>';
												}
											}
											?>
										</div>
									</div>
								</div>
							</div>
							<!--<div class="col-lg-6">
							 	<div class="well"> 
									<form class="form-horizontal" role="form">
									<h4>What's New</h4>
									<div class="form-group" style="padding:14px;">
										<textarea class="form-control" placeholder="Update your status"></textarea>
									</div>
									<button class="btn btn-primary pull-right" type="button">Post</button><ul class="list-inline"><li><a href=""><i class="glyphicon glyphicon-upload"></i></a></li><li><a href=""><i class="glyphicon glyphicon-camera"></i></a></li><li><a href=""><i class="glyphicon glyphicon-map-marker"></i></a></li></ul>
									</form>
								</div>
							</div>-->
							
						<!--<h4 class="text-center">
						<a href="http://bootply.com/96266" target="ext">Download this Template @Bootply</a>
						</h4>-->
					</div><!-- /col-9 -->
				</div><!-- /padding -->
			</div>
			<!-- /main -->
			
		</div>
	</div>
</div>


<!--Modals-->
<div id="addtrip-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				New Waybill for Trip
			</div>
			<div class="modal-body" style="padding: 10px;">
				<script>
				$(document).ready(function(){
					$("#addtrip-submitbutton").click(function() {
						checked = []
						$(".itemscheckbox:checked").each(function () {
								checked.push($(this).val())
						}); //Work out which items are coming!
						$.ajax({
							url: "ajax/insert_trip.php?" + "from=" + $("#addtrip-from").val() + "&to=" + $("#addtrip-to").val() + "&date=" + $("#addtrip-date").val() + "&items=" + checked.join(","), 
							success: function(result) {
								window.location.href = "?#triplist";
							}
						});
					});
				});
				</script>
				<form id="addtrip-form" class="form center-block" method="GET">
					<div class="form-group">
						<label>Date</label>
						<input type="date" id="addtrip-date" style="border: 0;" class="form-control  input-lg" />
					</div>
					<div class="form-group">
						<label for="addtrip-from">From</label>
						<select id="addtrip-from" style="border: 0;" class="form-control  input-lg">
						<?php
							$locationresult = mysqli_query($conn, "SELECT * FROM mary_locations WHERE showid='" . $_SESSION['showid'] . "'");
							while($locationrow = $locationresult->fetch_assoc()) {
								echo '<option value="' . $locationrow["id"] . '">' . $locationrow["name"] . ' (' . $locationrow["address"] . ')</option>';
							}
						?>
						</select>
					</div>
					<div class="form-group">
						<label>To</label>
						<select id="addtrip-to" style="border: 0;" class="form-control  input-lg">
						<?php
							$locationresult = mysqli_query($conn, "SELECT * FROM mary_locations WHERE showid='" . $_SESSION['showid'] . "'");
							while($locationrow = $locationresult->fetch_assoc()) {
								echo '<option value="' . $locationrow["id"] . '">' . $locationrow["name"] . ' (' . $locationrow["address"] . ')</option>';
							}
						?>
						</select>
					</div>
					<div class="form-group">
						<label>Items</label>
						<?php
							$waybillitemsresult = mysqli_query($conn, "SELECT * FROM mary_items WHERE showid='" . $_SESSION['showid'] . "'");
							while($waybillitemsrow = $waybillitemsresult->fetch_assoc()) {
								foreach (explode(",",$waybillitemsrow["niceid"]) AS $waybillitemsniceid) {
									echo '<div class="checkbox">
											<label><input type="checkbox" class="itemscheckbox" value="' . $waybillitemsniceid . '">' . $waybillitemsniceid . ' - ' . $waybillitemsrow['name'] . '</label>
										</div>';
								}
							}
						?>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="submit" id="addtrip-submitbutton" class="btn btn-default">Add</button>
			</div>
		</div>
	</div>
</div>
<div id="additem-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				Add Item
			</div>
			<div class="modal-body" style="padding: 10px;">
				<script>
				$(document).ready(function(){
					$("#additem-submitbutton").click(function() {
						$.ajax({
							url: "ajax/insert_item.php?niceid=" + $("#additem-niceid").val() + "&name=" + $("#additem-name").val() + "&description=" + $("#additem-description").val() + "&notes=" + $("#additem-notes").val() + "&hirebuy=" + $("#additem-hirebuy").val() + "&pricepaid=" + $("#additem-pricepaid").val() + "&value=" + $("#additem-value").val() + "&importance=" + $("#additem-importance").val() + "&personalto=" + $("#additem-personalto").val() + "&type=" + $("#additem-type").val() + "&owner=" + $("#additem-owner").val() + "&quantity=" + $("#additem-quantity").val()  + "&location=" + $("#additem-location").val(), 
							success: function(result) {
								if (result == "1") {
									window.location.href = "?addsuccess";
								} else {
									alert(result);
								}
							}
						});
					});
				});
				</script>
				<form id="additem-from" class="form center-block" method="GET">
					<div class="form-group">
						<label>Item ShortCode</label>
						<input type="text" disabled id="additem-niceid" style="border: 0;" value="<?=newniceid();?>" class="form-control  input-lg" />
					</div>
					<div class="form-group">
						<label for="additem-quantity">Quantity</label>
						<input type="number" required id="additem-quantity" style="border: 0;" value="1" class="form-control  input-lg" placeholder="Quantity" />
					</div>
					
					<div class="form-group">
						<label for="additem-name">Item Name</label>
						<input type="text" required id="additem-name" style="border: 0;" class="form-control  input-lg" autofocus placeholder="Item Name*" />
					</div>
					<div class="form-group">
						<label for="additem-description">Item Description</label>
						<textarea required class="form-control input-lg" style="border: 0;"  id="additem-description" placeholder="Item Description*"></textarea>
					</div>
					<div class="form-group">
						<label for="additem-notes">Notes<sup>Optional</sup></label>
						<textarea class="form-control input-lg" style="border: 0;"  id="additem-notes" placeholder="Item Notes"></textarea>
					</div>
					<div class="form-group">
						<label for="additem-hirebuy">Current Item Status</label>
						<select id="additem-hirebuy"  style="border: 0;" class="form-control  input-lg">
							<option class="info" value="1">Item has been loaned to production</option>
							<option class="warning" value="2">Item has been bought for production</option>
							<option class="danger" value="3">Item has been hired for production</option>
							<option class="default" selected value="0">Item has yet to be obtained for production</option>
						</select>
					</div>
					<div class="form-group">
						<label for="additem-owner">Item belongs to<sup>If Applicable</sup></label>
						<select id="additem-owner"  style="border: 0;" class="form-control  input-lg">
							<option selected value="0">Unknown/Not Applicable/ Not Listed</option>
							<?php
							$ownerresult = mysqli_query($conn, "SELECT * FROM mary_owners WHERE showid='" . $_SESSION['showid'] . "'");
							while($ownerrow = $ownerresult->fetch_assoc()) {
								echo '<option ' . ($row["owner"] == $ownerrow["id"] ? 'selected' : null) . ' value="' . $ownerrow["id"] . '">' . $ownerrow["name"] . '</option>';
							}
							?>
						</select>
						<i>To add someone to this list please contact support</i>
					</div>
					<div class="form-group">
						<label for="additem-pricepaid">Price Paid for Item (If Bought)<sup>Optional</sup></label>
						
						<table border="0" style="width: 100%;"><tr style="width: 100%;"><td><b>&pound;&nbsp;</b></td><td style="width: 100%;"><input type="text" required id="additem-pricepaid" style="border: 0;" class="form-control  input-lg" placeholder="Price Paid for Item" /></td></tr></table>
					</div>
					<div class="form-group">
						<label for="additem-value">Item Value<sup>Optional</sup></label>
						
						<table border="0" style="width: 100%;"><tr style="width: 100%;"><td><b>&pound;&nbsp;</b></td><td style="width: 100%;"><input type="text" required id="additem-value" style="border: 0;" class="form-control  input-lg" placeholder="Item Value" /></td></tr></table>
					</div>
					<div class="form-group">
						<label for="additem-importance">Item is</label>
						<select id="additem-importance"  style="border: 0;" class="form-control  input-lg">
							<?php
							$importanceresult = mysqli_query($conn, "SELECT * FROM mary_importance WHERE id != 0 ORDER BY rank ASC");
							while($importancerow = $importanceresult->fetch_assoc()) {
								echo '<option value="' . $importancerow["id"] . '">' . $importancerow["name"] . '</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="additem-personalto">Item is personal to</label>
						<select id="additem-personalto"  style="border: 0;" class="form-control  input-lg">
							<option selected value="0">Nobody</option>
							<?php
							$personaltoresult = mysqli_query($conn, "SELECT * FROM mary_cast WHERE showid='" . $_SESSION['showid'] . "'");
							while($personaltorow = $personaltoresult->fetch_assoc()) {
								echo '<option value="' . $personaltorow["id"] . '">' . ucwords(strtolower($personaltorow["name"])) . '</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="additem-type">Catagory</label>
						<select id="additem-type"  style="border: 0;" class="form-control  input-lg">
							<?php
							$typeresult = mysqli_query($conn, "SELECT * FROM mary_types WHERE id != 0 AND showid='" . $_SESSION['showid'] . "'");
							while($typerow = $typeresult->fetch_assoc()) {
								echo '<option value="' . $typerow["id"] . '">' . $typerow["plural"] . '</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="additem-location">Location</label>
						<select id="additem-location"  style="border: 0;" class="form-control  input-lg">
						<option selected value="0">Unknown</option>
							<?php
							$locationresult = mysqli_query($conn, "SELECT * FROM mary_locations WHERE showid='" . $_SESSION['showid'] . "'");
							while($locationrow = $locationresult->fetch_assoc()) {
								echo '<option value="' . $locationrow["id"] . '">' . $locationrow["name"] . ' (' . $locationrow["address"] . ')</option>';
							}
						?>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="submit" id="additem-submitbutton" class="btn btn-default">Add</button>
			</div>
		</div>
	</div>
</div>
<div id="listtypeall-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				PDF Property List
			</div>
			<div class="modal-body">
				<form class="form center-block" style="padding: 5px;">
					<p>Please select the options for the PDF property list you would like to generate:</p>
					<div class="form-group">
						<label for="list-description">Item Description</label>
						<select id="list-description"  style="border: 0;" class="form-control input-lg">
						  <option value="1">Show</option>
						  <option value="0" selected>Hide</option>
						</select>
					</div>
					<div class="form-group">
						<label for="list-location">Item Location</label>
						<select id="list-location"  style="border: 0;" class="form-control input-lg">
						  <option value="1" selected>Show</option>
						  <option value="0">Hide</option>
						</select>
					</div>
					<div class="form-group">
						<label for="list-value">Item Value</label>
						<select id="list-value"  style="border: 0;" class="form-control input-lg">
						  <option value="1">Show</option>
						  <option value="0" selected>Hide</option>
						</select>
					</div>
					<div class="form-group">
						<label for="list-pricepaid">Price Paid for Item (If Bought)</label>
						<select id="list-pricepaid"  style="border: 0;" class="form-control input-lg">
						  <option value="1">Show</option>
						  <option value="0" selected>Hide</option>
						</select>
					</div>
					<div class="form-group">
						<label for="list-importance">Item Importance to Show</label>
						<select id="list-importance"  style="border: 0;" class="form-control input-lg">
						  <option value="1">Show</option>
						  <option value="0" selected>Hide</option>
						</select>
					</div>
					<div class="form-group">
						<label for="list-niceid">Item ID <i>(Ex. 8EUHE)</i></label>
						<select id="list-niceid"  style="border: 0;" class="form-control input-lg">
						  <option value="1" selected>Show</option>
						  <option value="0">Hide</option>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button onclick="window.location.href = ('pdf/list.php?description=' + $('#list-description').val() + '&location=' + $('#list-location').val() + '&value=' + $('#list-value').val() + '&pricepaid=' + $('#list-pricepaid').val() + '&importance=' + $('#list-importance').val() + '&niceid=' + $('#list-niceid').val())" class="btn btn-primary btn-sm">Download</button>
			</div>
		</div>
	</div>
</div>
<?php
foreach ($types as $type) {
?>
<div id="listtype<?=strtolower($type["name"])?>-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<?=$type["name"]?> Items PDF List
			</div>
			<div class="modal-body">
				<form class="form center-block" style="padding: 5px;">
					<p>Please select the options for the PDF you would like to generate:</p>
					<!--<div class="form-group">
						<label for="list-type">Show Items from Catagories:</label>
						
						<?php
						/*$type = '';
						$counter = 0; 
						foreach ($types as $checkboxtype) {
							echo '<div class="checkbox">
									<label><input type="checkbox" id="list-type' .  $checkboxtype["id"] . '" ' . ($type["id"] == $checkboxtype["id"] ? 'checked' : null) . ' value="' . $checkboxtype["id"] . '">' . $checkboxtype["plural"] . '</label>
									</div>';
							if ($counter != 0) $type .= ' + ';
							$type .= "($('#list-type" . $checkboxtype["id"] . "').is(':checked') ? '" . ($type != '' ? ',' : '') . $checkboxtype["id"] . "' : '')";
							$counter ++;
						}*/
						?>
					</div>-->
					<div class="form-group">
						<label for="list-description">Item Description</label>
						<select id="list-description"  style="border: 0;" class="form-control input-lg">
						  <option value="1">Show</option>
						  <option value="0" selected>Hide</option>
						</select>
					</div>
					<div class="form-group">
						<label for="list-location">Item Location</label>
						<select id="list-location"  style="border: 0;" class="form-control input-lg">
						  <option value="1" selected>Show</option>
						  <option value="0">Hide</option>
						</select>
					</div>
					<div class="form-group">
						<label for="list-value">Item Value</label>
						<select id="list-value"  style="border: 0;" class="form-control input-lg">
						  <option value="1">Show</option>
						  <option value="0" selected>Hide</option>
						</select>
					</div>
					<div class="form-group">
						<label for="list-pricepaid">Price Paid for Item (If Bought)</label>
						<select id="list-pricepaid"  style="border: 0;" class="form-control input-lg">
						  <option value="1">Show</option>
						  <option value="0" selected>Hide</option>
						</select>
					</div>
					<div class="form-group">
						<label for="list-importance">Item Importance to Show</label>
						<select id="list-importance"  style="border: 0;" class="form-control input-lg">
						  <option value="1">Show</option>
						  <option value="0" selected>Hide</option>
						</select>
					</div>
					<div class="form-group">
						<label for="list-niceid">Item ID <i>(Ex. 8EUHE)</i></label>
						<select id="list-niceid"  style="border: 0;" class="form-control input-lg">
						  <option value="1" selected>Show</option>
						  <option value="0">Hide</option>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button onclick="window.location.href = ('pdf/list.php?type=<?php echo $type["id"]; ?>&description=' + $('#list-description').val() + '&location=' + $('#list-location').val() + '&value=' + $('#list-value').val() + '&pricepaid=' + $('#list-pricepaid').val() + '&importance=' + $('#list-importance').val() + '&niceid=' + $('#list-niceid').val())" class="btn btn-primary btn-sm">Download</button>
			</div>
		</div>
	</div>
</div>
<?php
}
?>
<div id="support-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				Help &amp; Support
			</div>
			<div class="modal-body">
				<center>
					<h1>Support!</h1>
					<p>Contact James Bithell using James.Bithell@westminster.org.uk or if urgent <a href="tel:00447706014590">00447706014590</a>.
				</center>
			</div>
			<div class="modal-footer">
				<div>
					<a href="tel:00447706014590"><button class="btn btn-danger btn-sm">Call</button></a>
				</div>	
			</div>
		</div>
	</div>
</div>
<?php
$sql = "SELECT * FROM mary_cast WHERE showid='" . $_SESSION['showid'] . "'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {
		 echo '<div id="castmember' . $row["id"] . '-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								' . ucwords(strtolower($row["name"])) . '
							</div>
							<div class="modal-body" style="padding: 10px;">
							<h3>Personal Items</h3>';
							
							$itemssql = "SELECT * FROM mary_items WHERE personalto='" . $row["id"] . "' AND showid='" . $_SESSION['showid'] . "'";
							$itemsresult = $conn->query($itemssql);
							if ($result->num_rows > 0) {
								 while($itemsrow = $itemsresult->fetch_assoc()) {
									echo '<ul class="list-group">
										<li class="list-group-item"><span class="badge">' . $itemsrow["quantity"] . '</span> ' . $itemsrow["name"] . '</li>
									</ul>';
								 }
							} else {
								echo '<center><i>Cast Member has no personal items</i></center>';
							}
							echo '</div>
							<!--<div class="modal-footer">
	
							</div>-->
						</div>
					</div>
				</div>';
	 }
}

?>
<?php
$sql = "SELECT mary_items.*, mary_cast.name AS personaltoname, mary_owners.name AS ownername, mary_locations.name AS locationname, mary_locations.address AS locationaddress, mary_importance.name AS importancename, mary_types.name AS typename  FROM mary_items 
LEFT JOIN mary_cast
ON mary_items.personalto = mary_cast.id 
LEFT JOIN mary_owners
ON mary_items.owner = mary_owners.id
LEFT JOIN mary_locations
ON mary_items.location = mary_locations.id
LEFT JOIN mary_importance
ON mary_items.importance = mary_importance.id
LEFT JOIN mary_types
ON mary_items.type = mary_types.id

WHERE mary_items.showid='" . $_SESSION['showid'] . "'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	 while($row = $result->fetch_assoc()) {
		 echo '<div id="item' . $row["id"] . '-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' . 
								($row["quantity"] > 1 ? '<span class="badge">' . $row["quantity"] . 'x</span> ' : null) . ucwords(strtolower($row["name"])) . '
							</div>
							<div class="modal-body" style="padding: 10px;">
							<center><pre>' . str_replace(",","<br/>",$row["niceid"]) . '</pre></center>
							<center><p>' . $row["description"] . '</p></center>
							<table class="table">
								<!--<thead></thead>-->
								<tbody>';
									if ($row["notes"] != null) echo '<tr><td>' . $row["notes"] . '</td></tr>'; //Notes
									
									//HireBuy
									echo '<tr>';
											if ($row["hirebuy"] == 1) echo '<td class="info">Item has been loaned to production</td>';
													elseif ($row["hirebuy"] == 2) echo '<td class="warning">Item has been bought for production</td>';
													elseif ($row["hirebuy"] == 3) echo '<td class="danger">Item has been hired for production</td>';
													else echo '<td class="default">Item has yet to be obtained for production</td>';
										echo '</tr>';
										
									if ($row["hirebuy"] == 2) echo '<tr><td>' . ($row["pricepaid"] != null ? '£' . $row["value"] . ' was paid for this item' : 'The amount paid for the item has not been recorded') . '</td></tr>'; //Pricepaid
									
									echo '<tr><td>' . ($row["value"] != null ? 'Item has been valued at &pound;' . $row["value"] : 'Item has not been valued') . '</td></tr>'; //Value
									
									echo '<tr><td>' . ($row["importance"] != "0" ? '<b>Importance:</b> Item is ' . $row["importancename"] : 'Item\'s importance has not been assessed') . '</td></tr>'; //importance
									
									echo '<tr><td>' . ($row["personalto"] != "0" ? 'Item is personal to ' . ucwords(strtolower($row["personaltoname"])) : 'Item is not personal') . '</td></tr>'; //Personalto
									
									echo '<tr><td>' . ($row["location"] != "0" ? 'Item is in ' . $row["locationname"] . ' (' . $row["locationaddress"] . ')' : 'Item\'s location is unknown') . '</td></tr>'; //Location
									
									echo '</tbody>
								</table>
							</div>
							<div class="modal-footer">';
								if ($edit) echo '<a href="#item' . $row["id"] . '-delete-modal" data-toggle="modal"><button class="btn btn-danger" type="button"><i class="fa fa-trash"></i></button></a>';
								
								if ($edit) echo '<a href="#item' . $row["id"] . '-edit-modal" data-toggle="modal"><button class="btn btn-default" type="button"><i class="fa fa-pencil"></i></button></a>';
								
							echo '</div>
						</div>
					</div>
				</div>';
	 }
	 
	 if ($edit)  {
		$result = $conn->query($sql); //Query again
		while($row = $result->fetch_assoc()) {
			 echo '<div id="item' . $row["id"] . '-edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' . 
									($row["quantity"] > 1 ? '<span class="badge">' . $row["quantity"] . 'x</span> ' : null) . ucwords(strtolower($row["name"])) . '
								</div>
								<div class="modal-body" style="padding: 10px;">
									<script>
									$(document).ready(function(){
										$("#submititem' . $row["id"] . '-edit").click(function() {
											$.ajax({
												url: "ajax/edit_item.php?itemid=' . $row["id"] . '&name=" + $("#edititem' . $row["id"] . '-name").val() + "&description=" + $("#edititem' . $row["id"] . '-description").val() + "&notes=" + $("#edititem' . $row["id"] . '-notes").val() + "&hirebuy=" + $("#edititem' . $row["id"] . '-hirebuy").val() + "&pricepaid=" + $("#edititem' . $row["id"] . '-pricepaid").val() + "&value=" + $("#edititem' . $row["id"] . '-value").val() + "&importance=" + $("#edititem' . $row["id"] . '-importance").val() + "&personalto=" + $("#edititem' . $row["id"] . '-personalto").val() + "&type=" + $("#edititem' . $row["id"] . '-type").val() + "&owner=" + $("#edititem' . $row["id"] . '-owner").val() + "&quantity=" + $("#edititem' . $row["id"] . '-quantity").val() + "&location=" + $("#edititem' . $row["id"] . '-location").val(), 
												success: function(result) {
													if (result == "1") {
														window.location.href = "?updatesuccess";
													} else {
														alert(result);
													}
												}
											});
										});
									});
									</script>
									<form id="formitem' . $row["id"] . '-edit" class="form center-block" method="GET">
										<div class="form-group">
											<label>Item ShortCode</label>
											<input type="text" disabled style="border: 0;" class="form-control  input-lg" value="' . $row["niceid"] . '" />
										</div>
										<div class="form-group">
											<label for="edititem' . $row["id"] . '-quantity">Quantity</label>
											<input type="number" required id="edititem' . $row["id"] . '-quantity" style="border: 0;" class="form-control  input-lg" value="' . $row["quantity"] . '" placeholder="Quantity" />
										</div>
										
										<div class="form-group">
											<label for="edititem' . $row["id"] . '-name">Item Name</label>
											<input type="text" required id="edititem' . $row["id"] . '-name" style="border: 0;" class="form-control  input-lg" autofocus value="' . $row["name"] . '" placeholder="Item Name*" />
										</div>
										<div class="form-group">
											<label for="edititem' . $row["id"] . '-description">Item Description</label>
											<textarea required class="form-control input-lg" style="border: 0;"  id="edititem' . $row["id"] . '-description" placeholder="Item Description*">' . $row["description"] . '</textarea>
										</div>
										<div class="form-group">
											<label for="edititem' . $row["id"] . '-notes">Notes<sup>Optional</sup></label>
											<textarea class="form-control input-lg" style="border: 0;"  id="edititem' . $row["id"] . '-notes" placeholder="Item Notes">' . $row["notes"] . '</textarea>
										</div>
										<div class="form-group">
											<label for="edititem' . $row["id"] . '-hirebuy">Current Item Status</label>
											<select id="edititem' . $row["id"] . '-hirebuy"  style="border: 0;" class="form-control  input-lg">
												<option class="info" ' . ($row["hirebuy"] == "1" ? 'selected' : null) . ' value="1">Item has been loaned to production</option>
												<option class="warning" ' . ($row["hirebuy"] == "2" ? 'selected' : null) . ' value="2">Item has been bought for production</option>
												<option class="danger" ' . ($row["hirebuy"] == "3" ? 'selected' : null) . ' value="3">Item has been hired for production</option>
												<option class="default" ' . ($row["hirebuy"] == "0" ? 'selected' : null) . ' value="0">Item has yet to be obtained for production</option>
											</select>
										</div>
										<div class="form-group">
											<label for="edititem' . $row["id"] . '-owner">Item belongs to<sup>If Applicable</sup></label>
											<select id="edititem' . $row["id"] . '-owner"  style="border: 0;" class="form-control  input-lg">
												<option ' . ($row["owner"] == "0" ? 'selected' : null) . ' value="0">Unknown/Not Applicable/ Not Listed</option>';
												$ownerresult = mysqli_query($conn, "SELECT * FROM mary_owners WHERE showid='" . $_SESSION['showid'] . "'");
												while($ownerrow = $ownerresult->fetch_assoc()) {
													echo '<option ' . ($row["owner"] == $ownerrow["id"] ? 'selected' : null) . ' value="' . $ownerrow["id"] . '">' . $ownerrow["name"] . '</option>';
												}
											echo '</select>
											<i>To add someone to this list please contact support</i>
										</div>
										<div class="form-group">
											<label for="edititem' . $row["id"] . '-pricepaid">Price Paid for Item (If Bought)<sup>Optional</sup></label>
											
											<table border="0" style="width: 100%;"><tr style="width: 100%;"><td><b>&pound;&nbsp;</b></td><td style="width: 100%;"><input type="text" required id="edititem' . $row["id"] . '-pricepaid" style="border: 0;" class="form-control  input-lg" value="' . $row["pricepaid"] . '" placeholder="Price Paid for Item" /></td></tr></table>
										</div>
										<div class="form-group">
											<label for="edititem' . $row["id"] . '-value">Item Value<sup>Optional</sup></label>
											
											<table border="0" style="width: 100%;"><tr style="width: 100%;"><td><b>&pound;&nbsp;</b></td><td style="width: 100%;"><input type="text" required id="edititem' . $row["id"] . '-value" style="border: 0;" class="form-control  input-lg" value="' . $row["value"] . '" placeholder="Item Value" /></td></tr></table>
										</div>
										<div class="form-group">
											<label for="edititem' . $row["id"] . '-importance">Item is</label>
											<select id="edititem' . $row["id"] . '-importance"  style="border: 0;" class="form-control  input-lg">';
												$importanceresult = mysqli_query($conn, "SELECT * FROM mary_importance WHERE id != 0 ORDER BY rank ASC");
												while($importancerow = $importanceresult->fetch_assoc()) {
													echo '<option ' . ($row["importance"] == $importancerow["id"] ? 'selected' : null) . ' value="' . $importancerow["id"] . '">' . $importancerow["name"] . '</option>';
												}
											echo '</select>
										</div>
										<div class="form-group">
											<label for="edititem' . $row["id"] . '-personalto">Item is personal to</label>
											<select id="edititem' . $row["id"] . '-personalto"  style="border: 0;" class="form-control  input-lg">
												<option ' . ($row["personalto"] == "0" ? 'selected' : null) . ' value="0">Nobody</option>';
												$personaltoresult = mysqli_query($conn, "SELECT * FROM mary_cast WHERE showid='" . $_SESSION['showid'] . "'");
												while($personaltorow = $personaltoresult->fetch_assoc()) {
													echo '<option ' . ($row["personalto"] == $personaltorow["id"] ? 'selected' : null) . ' value="' . $personaltorow["id"] . '">' . ucwords(strtolower($personaltorow["name"])) . '</option>';
												}
											echo '</select>
										</div>
										<div class="form-group">
											<label for="edititem' . $row["id"] . '-type">Catagory</label>
											<select id="edititem' . $row["id"] . '-type"  style="border: 0;" class="form-control  input-lg">';
												$typeresult = mysqli_query($conn, "SELECT * FROM mary_types WHERE id != 0 AND showid='" . $_SESSION['showid'] . "'");
												while($typerow = $typeresult->fetch_assoc()) {
													echo '<option ' . ($row["type"] == $typerow["id"] ? 'selected' : null) . ' value="' . $typerow["id"] . '">' . $typerow["plural"] . '</option>';
												}
											echo '</select>
										</div>
										<div class="form-group">
											<label for="edititem' . $row["id"] . '-location">Location</label>
											<select id="edititem' . $row["id"] . '-location"  style="border: 0;" class="form-control  input-lg">';
											echo '<option ' . ($row["location"] == "0" ? 'selected' : null) . ' value="0">Unknown</option>';
												$locationresult = mysqli_query($conn, "SELECT * FROM mary_locations WHERE showid='" . $_SESSION['showid'] . "'");
												while($locationrow = $locationresult->fetch_assoc()) {
													echo '<option ' . ($row["location"] == $locationrow["id"] ? 'selected' : null) . ' value="' . $locationrow["id"] . '">' . $locationrow["name"] . ' (' . $locationrow["address"] . ')</option>';
												}
											echo '</select>
										</div>
									</form>
								</div>
								<div class="modal-footer">
									<button type="submit" id="submititem' . $row["id"] . '-edit" class="btn btn-default">Save</button>
								</div>
							</div>
						</div>
					</div>';
		 }
		 
		$result = $conn->query($sql); //Query again - for delete modal
		while($row = $result->fetch_assoc()) {
			 echo '<div id="item' . $row["id"] . '-delete-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<script>
									$(document).ready(function(){
										$("#deletebutton' . $row["id"] . '").click(function() {
											$.ajax({
												url: "ajax/delete_item.php?itemid=' . $row["id"] . '", 
												success: function(result) {
													if (result == "1") {
														window.location.href = "?deletesuccess";
													} else {
														alert(result);
													}
												}
											});
										});
									});
								</script>
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									Delete - ' . ($row["quantity"] > 1 ? '<span class="badge">' . $row["quantity"] . 'x</span> ' : null) . ucwords(strtolower($row["name"])) . '
								</div>
								<div class="modal-body" style="padding: 10px;">
									Are you sure you wish to delete this item? This action cannot be undone.
									<br/><br/>
									You have been warned!
								</div>
							</div>
							<div class="modal-footer">
								<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
								<button id="deletebutton' . $row["id"] . '" class="btn btn-danger">Delete</button>
							</div>
						</div>
					</div>';
		 }
	 }
}

?>
<?php
if (isset($_GET["q"])) {
	$sql = "SELECT id, niceid, name FROM mary_items WHERE (name LIKE '%" . mysqli_real_escape_string($conn, $_GET["q"]) . "%' OR description LIKE '%" . mysqli_real_escape_string($conn, $_GET["q"]) . "%' OR niceid LIKE '%" . mysqli_real_escape_string($conn, $_GET["q"]) . "%' OR notes LIKE '%" . mysqli_real_escape_string($conn, $_GET["q"]) . "%' ) AND showid='" . $_SESSION['showid'] . "'";
	$result = $conn->query($sql);
	echo '<div id="search-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<script>
							$(document).ready(function(){
								$("#infolinksearch").click(function() {
									$("#search-modal").modal(\'hide\');
								});
							});
							</script>
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								Search results for ' . $_GET["q"] . '
							</div>
							<div class="modal-body">';
	if ($result->num_rows > 0) {
		echo '<table class="table table-striped">
				<thead>
				  <tr>
					<th style="width: 80px;"></th>
					<th>Item</th>
					<th style="width: 10px;"></th>
				  </tr>
				</thead>
				<tbody>';
		while($row = $result->fetch_assoc()) {
			echo '<tr>';
			echo '<td>' . str_replace(",","<br/>",$row["niceid"]) . '</td><td>' . $row["name"] . '</td><td>' . '<a id="infolinksearch" href="#item' . $row["id"] . '-modal" data-toggle="modal"><button class="btn btn-default" type="button"><i class="fa fa-info"></i></button></a>' . '</td>';
			echo '</tr>';
		}
		echo '</tbody></table>';
	} else echo '<center><i>No Results - Sorry!</i></center>';
	echo '</div>
			</div>
				</div>
				<script>
							$(document).ready(function(){
								$("#search-modal").modal(\'show\');
							});
							</script>
							';
	}
/*if (isset($user)) {
	echo '<div id="changepass-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					Change your Password
				</div>
				<div class="modal-body" style="padding: 10px;">
					<script>
					$(document).ready(function(){
						$("#changepass-submitbutton").click(function() {
							if ($("#changepass-new").val() == $("#changepass-newconf").val()) {
								$.ajax({
									url: "ajax/changepass.php?id=' . $user["id"] . '&pass=" + $("#changepass-current").val() + "&newpass=" + $("#changepass-new").val(), 
									success: function(result) {
										if (result == "1") {
											window.location.href = "?changepasssuccess";
										} else if (result == "2") {
											bootbox.alert("Password incorrect - Please try again!");
										} else {
											alert(result);
										}
									}
								});
							} else {
								bootbox.alert("Passwords don\'t match!");
							}
						});
					});
					</script>
					<form id="additem-from" class="form center-block" method="GET">
						<div class="form-group">
							<label>Current Password</label>
							<input type="password" id="changepass-current" style="border: 0;" class="form-control  input-lg" />
						</div>
						<div class="form-group">
							<label>New Password</label>
							<input type="password" id="changepass-new" style="border: 0;" class="form-control  input-lg" />
						</div>
						<div class="form-group">
							<label>Confirm New Password</label>
							<input type="password" id="changepass-newconf" style="border: 0;" class="form-control  input-lg" />
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="submit" id="changepass-submitbutton" class="btn btn-default">Change</button>
				</div>
			</div>
		</div>
	</div>';
}*/
?>
	</body>
</html>