<?php

/* (Reference #15) */

if ($_COOKIE['auth'] == "1") {
	$display_block = "<p>User authorization confirmed.</p>";
} else {
	//redirect back to login form if not authorized
	header("Location: login.html");
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Authorization Confirmation</title>
</head>
<body>
<h2>Great Lakes Super Bookstore</h2>
<h2>Software Engineering 700, Spring 2015</h2>
<?php echo $display_block; ?>
</body>
</html>
