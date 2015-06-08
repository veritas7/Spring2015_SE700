<?php

/* (References #13 and #14) */

include 'db_connect_include.php';  //this included file will establish the MySQL database connection

	doDB();

//check for required fields from the form
if ((!isset($_POST['username'])) || (!isset($_POST['password']))) {
    header("Location: login.html");
    exit;
}

	  
//use mysqli_real_escape_string to clean the input
$username = mysqli_real_escape_string($mysqli, $_POST['username']);
$password = mysqli_real_escape_string($mysqli, $_POST['password']);

//create and issue the query
$sql = "SELECT firstName, lastName FROM customer WHERE
        customerID = '".$username."' AND
        password = PASSWORD('".$password."')";

$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

//get the number of rows in the result set; should be 1 if a match
if (mysqli_num_rows($result) == 1) {

    //if authorized, get the values of f_name l_name
    while ($info = mysqli_fetch_array($result)) {
         $f_name = stripslashes($info['firstName']);
         $l_name = stripslashes($info['lastName']);
    }

    //set authorization cookie
    //setcookie("auth", "1", 0, "/", "yourdomain.com", 0);
	setcookie("auth", "1", 0, "/", "localhost", 0);

    //create display string
    $display_block = "
    <p>".$f_name." ".$l_name." is authorized!</p>
    <p>Authorized Users' Menu:</p>
    <ul>
    <li><a href=\"auth_confirm.php\">Authorization Confirmation</a></li>
    </ul>";
} else {
    //redirect back to login form if not authorized
    header("Location: login.html");
    exit;
}

//close connection to MySQL
mysqli_close($mysqli);
?>
<!DOCTYPE html>
<html>
<head>
<title>User Login</title>
</head>
<body>
<h2>Great Lakes Super Bookstore</h2>
<h2>Software Engineering 700, Spring 2015</h2>
<?php echo $display_block; ?>
</body>
</html>
