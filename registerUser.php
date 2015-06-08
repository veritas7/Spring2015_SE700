<?php

/* (Reference #11) */

include 'db_connect_include.php';  //this include file will establish the MySQL database connection

if (!$_POST) {
	
	$display_block = <<<END_OF_TEXT
	<form method="post" action="$_SERVER[PHP_SELF]">
	
	<p><label for="customerID"><strong>username:</strong></label><br/>
	<input type="text" id="customerID" name="customerID" required /><p>
	 <!-- text box to receive the customerID, email address -->
	
	<p><label for="password"><strong>password:</strong></label><br/>
	<input type="password" id="password" name="password" required /></p>
	<!-- text box to receive the password -->
	
	<p><label for="firstName">First Name:</label>
	
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	
	<label for="lastName">Last Name:</label><br/>
	
	<input type="text" name="firstName" size="30" maxlength="75" required />
	<!-- text box to receive the first name -->
	
	<input type="text" name="lastName" size="30" maxlength="75" required />
	<!-- text box to receive the last name -->
	
	
	
	
	<p><label for="street">Street:</label>
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	&nbsp&nbsp&nbsp&nbsp&nbsp
	<label for="cityTown">City:</label><br/>
	
	<input type="text" id="street" name="street" size="30" />
	<!-- text box to receive the street -->
	
	
	<input type="text" name="cityTown" size="30" maxlength="50" />
	<!-- text box to receive the city or town name -->
	
	<p><label for="state">State:</label>
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	<label for="zipCode">Zip Code:</label><br/>
	
	<input type="text" name="state" size="5" maxlength="2" />
	<!-- text box to receive the state -->
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	&nbsp&nbsp&nbsp
	
	<input type="text" name="zipCode" size="10" maxlength="10" /></p>
	<!-- text box to receive the zip code -->
	
	<p><label for="phone">Telephone Number:</label><br/>
	<input type="text" name="phone" size="30" maxlength="25" />
	<!-- text box to receive the phone number -->
	
	<BR><BR>

	<button type="submit" name="submit" value="send">Create My Account</button>
	<!-- this is the submit button -->
	
	</form>
END_OF_TEXT;

} else if ($_POST) {
	//this section sets the required fields, which, in this case, are username, password, first and last names
	if ( ($_POST['customerID'] == "") || ($_POST['password'] == "") || ($_POST['firstName'] == "") || ($_POST['lastName'] == "")  ) {
		header("Location: registerUser.php");
		exit;
	}

	// the doDB function below is instantiated by calling db_connect_include.php
	doDB();


	$safe_username = mysqli_real_escape_string($mysqli, $_POST['customerID']);
	//as one example $safe_username is the 'sanitized' version of customerID to avoid SQL injection attack
	
	$safe_password = mysqli_real_escape_string($mysqli, $_POST['password']);
	$safe_f_name = mysqli_real_escape_string($mysqli, $_POST['firstName']);
	$safe_l_name = mysqli_real_escape_string($mysqli, $_POST['lastName']);
	$safe_address = mysqli_real_escape_string($mysqli, $_POST['street']);
	$safe_city = mysqli_real_escape_string($mysqli, $_POST['cityTown']);
	$safe_state = mysqli_real_escape_string($mysqli, $_POST['state']);
	$safe_zipcode = mysqli_real_escape_string($mysqli, $_POST['zipCode']);
	$safe_tel_number = mysqli_real_escape_string($mysqli, $_POST['phone']);

	
	//this performs the SQL INSERT operation to populate the customer table
	$add_master_sql = "INSERT INTO customer (date_added, date_modified, firstName, lastName, customerID, password)
                       VALUES (now(), now(), '".$safe_f_name."', '".$safe_l_name."', '".$safe_username."', PASSWORD('".$safe_password."'))";  
					   
	$add_master_res = mysqli_query($mysqli, $add_master_sql) or die(mysqli_error($mysqli));

	
	$master_id = mysqli_insert_id($mysqli);

	if (($_POST['street']) || ($_POST['cityTown']) || ($_POST['state']) || ($_POST['zipCode'])) {
		
	//this performs the SQL INSERT operation to populate the address table
		$add_address_sql = "INSERT INTO address (master_id, date_added, date_modified,
		                    street, cityTown, state, zipCode)  VALUES ('".$master_id."',
		                    now(), now(), '".$safe_address."', '".$safe_city."',
		                    '".$safe_state."' , '".$safe_zipcode."')";
		$add_address_res = mysqli_query($mysqli, $add_address_sql) or die(mysqli_error($mysqli));
	}
	
	if ($_POST['phone']) {
		//this performs the SQL INSERT operation to populate the telephone table
		$add_tel_sql = "INSERT INTO telephone (master_id, date_added, date_modified,
		                phone)  VALUES ('".$master_id."', now(), now(),
		                '".$safe_tel_number."')";
		$add_tel_res = mysqli_query($mysqli, $add_tel_sql) or die(mysqli_error($mysqli));
	}

	mysqli_close($mysqli);
	$display_block = "<p>Your account has been created.  Please <a href=\"login.html\">login</a> to your new account.</p>";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>New User Account Page</title>
</head>
<body>
<h2>Great Lakes Super Bookstore</h2>
<h2>Software Engineering 700, Spring 2015</h2>
<h2>New Customer Registration Page</h2>
<?php echo $display_block; ?>
</body>
</html>