<?php
	session_start();
	if (isset($_GET['error'])) {
		$error=$_GET['error'];
		if ($error="captcha") {
			?> <script type="text/javascript">alert("Please check the captcha box.");</script> <?php
		}
		else{
			?> <script type="text/javascript">alert("Please fill in all the fields properly.");</script> <?php
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>CREATE NEW PASSWORD</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
  	<link rel="stylesheet" type="text/css" href="style/homestyle.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<?php
	require_once 'includes/usernav.php';
	if (isset($_GET['selector']) && isset($_GET['validator'])) {
		$selector=$_GET['selector'];
		$validator=$_GET['validator'];
	}
	else{
		echo "INVALID REQUEST!!";
	}

	if (empty($selector) || empty($validator)) {
		echo "Sorry couldnot validate your request.";
	}
	else{
		if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) {
			?>
			<center>
				<h1>RESET YOUR PASSWORD</h1>
				<form method="POST" action="includes/new-password-process.php">
					<label><h3>Enter new Password</h3></label><br>
					<input type="password" name="pwd" placeholder="New password" required><br><br>
					<label><h3>Re-enter the password</h3></label><br>
					<input type="password" name="pwd-repeat" placeholder="Re-enter password" required><br><br>
					<input type="hidden" name="validator" value="<?php echo $validator; ?>">
					<input type="hidden" name="selector" value="<?php echo $selector; ?>"><br><br>
					<div class="g-recaptcha" data-sitekey="6LeTO40UAAAAAB5-rLh0MeipD6W0bxWwqzu5n_wZ"></div>
					<input type="submit" name="submit" value="RESET PASSWORD"> 
				</form>
			</center>
			<?php
		}
	}
?>
</body>
</html>