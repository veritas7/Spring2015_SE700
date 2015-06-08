<?php

/* (Reference #17) */

include 'db_connect_include.php';  //contains the doDB() function below

doDB();

if (!$_POST)  {
	//haven't seen the selection form, so show it
	$display_block = "<h2>User Account Management System</h2>";

	//get parts of records
	$get_list_sql = "SELECT id,
	                 CONCAT_WS(', ', lastName, firstName, customerID) AS display_name
	                 FROM customer ORDER BY lastName, firstName";
	$get_list_res = mysqli_query($mysqli, $get_list_sql) or die(mysqli_error($mysqli));

	if (mysqli_num_rows($get_list_res) < 1) {
		//no records
		$display_block .= "<p><em>No users found.</em></p>";

	} else {
		//has records, so get results and print in a form
		$display_block .= "
		<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">
		<p><label for=\"sel_id\">Select a User Account:</label><br/>
		<select id=\"sel_id\" name=\"sel_id\" required=\"required\">
		<option value=\"\">-- Select One --</option>";

		while ($recs = mysqli_fetch_array($get_list_res)) {
			$id = $recs['id'];
			$display_name = stripslashes($recs['display_name']);
			$display_block .= "<option value=\"".$id."\">".$display_name."</option>";
		}

		$display_block .= "
		</select></p>
		<button type=\"submit\" name=\"submit\" value=\"view\">View User Account</button>
		</form>";
	}
	//free result
	mysqli_free_result($get_list_res);

} else if ($_POST) {
	//check for required fields
	if ($_POST['sel_id'] == "")  {
		header("Location: select_user.php");
		exit;
	}

	//create safe version of ID
	$safe_id = mysqli_real_escape_string($mysqli, $_POST['sel_id']);

	//get master_info
	$get_master_sql = "SELECT concat_ws(' ', firstName, lastName, customerID) as display_name
	                   FROM customer WHERE id = '".$safe_id."'";
	$get_master_res = mysqli_query($mysqli, $get_master_sql) or die(mysqli_error($mysqli));

	while ($name_info = mysqli_fetch_array($get_master_res)) {
		$display_name = stripslashes($name_info['display_name']);
	}
	
	$display_block = "<h1>Showing Record for </h1> <h1>".$display_name."</h1>";

	//free result
	mysqli_free_result($get_master_res);

	//get all addresses
	$get_addresses_sql = "SELECT street, cityTown, state, zipCode
	                      FROM address WHERE master_id = '".$safe_id."'";
	$get_addresses_res = mysqli_query($mysqli, $get_addresses_sql) or die(mysqli_error($mysqli));

 	if (mysqli_num_rows($get_addresses_res) > 0) {

		$display_block .= "<p><strong>Addresses:</strong><br/>
		<ul>";

		while ($add_info = mysqli_fetch_array($get_addresses_res)) {
			$street = stripslashes($add_info['street']);
			$cityTown = stripslashes($add_info['cityTown']);
			$state = stripslashes($add_info['state']);
			$zipCode = stripslashes($add_info['zipCode']);
			

			$display_block .= "<li>$street $cityTown $state $zipCode</li>";
		}

		$display_block .= "</ul>";
	}

	//free result
	mysqli_free_result($get_addresses_res);

	//get all tel
	$get_tel_sql = "SELECT phone FROM telephone
	                WHERE master_id = '".$safe_id."'";
	$get_tel_res = mysqli_query($mysqli, $get_tel_sql) or die(mysqli_error($mysqli));

	if (mysqli_num_rows($get_tel_res) > 0) {

		$display_block .= "<p><strong>Telephone:</strong><br/>
		<ul>";

		while ($tel_info = mysqli_fetch_array($get_tel_res)) {
			$phone = stripslashes($tel_info['phone']);
			
			$display_block .= "<li>$phone</li>";
			
		}

		$display_block .= "</ul>";
	}

	//free result
	mysqli_free_result($get_tel_res);

	$display_block .= "<br/>
	<p style=\"text-align: center\"><a href=\"".$_SERVER['PHP_SELF']."\">Select Another User</a></p>";
}
//close connection to MySQL
mysqli_close($mysqli);
?>
<!DOCTYPE html>
<html>
<head>
<title>GLSB Manage Users</title>
</head>
<body>
<h2>Great Lakes Super Bookstore</h2>
<h2>Software Engineering 700, Spring 2015</h2>
<?php echo $display_block; ?>
</body>
</html>