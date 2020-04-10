<!DOCTYPE html>
<html>
<head>
	<title>FORGOT PASSWORD</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
  	<link rel="stylesheet" type="text/css" href="style/homestyle.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<?php require_once 'includes/usernav.php'; ?>
	<center>
		<h1>RESET YOUR PASSWORD</h1>
		<h2>An e-mail will be sent to you instructing how to reset your password.</h2>
		<form action="includes/forgot-process.php" method="POST">
			<label><h2>Enter your e-mail address</h2></label>
			<input type="email" name="email" autocomplete="off" style="width: 400px;height: 50px;padding: 0 20px 0 20px;font-size: 1.3em;"><br><br><br>
			<input type="submit" name="submit" value="GET EMAIL" style="border-radius: 0;width: 200px;">
		</form>
		<?php
			if (isset($_GET['reset'])) {
				if ($_GET['reset']=="success") {
					echo "<h3>Check your e-mail for more details. </h3>";
				}
			}
		?>
	</center>
</body>
</html>