<?php
	include('includes/dbConn.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>SIGN UP</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="style/style.css">
</head>
<body>
  	<h1 style="text-align: center;margin-bottom: 10px;">Register With Us</h1>
	<?php
		$error="";
		if (isset($_GET['err'])) {
			$error=htmlspecialchars($_GET['err']);
		}

		if ($error=="empty") {
			echo '<center><p class="error">Please fill in all the fields</p></center>';
		}
		else if ($error=="name") {
			echo '<center><p class="error">Invalid name</p></center>';
		}
		elseif ($error=="email") {
			echo '<center><p class="error">Invalid email address</p></center>';
		}
		elseif ($error=="mob") {
			echo '<center><p class="error">Invalid mobile number</p></center>';
		}
		elseif($error=="pass"){
			echo '<center><p class="error">Password should be atleast six characters long</p></center>';
		}
		elseif ($error=="passmis") {
			echo '<center><p class="error">The passwords do not match.</p></center>';
		}
		elseif ($error=="usertaken") {
			echo '<center><p class="error">This email id is already registered with us.</p></center>';
		}
		elseif ($error=="mobtaken") {
			echo '<center><p class="error">This mobile number is already registered with us.</p></center>';
		}

		$login="";
		if (isset($_GET['login'])) {
			$login=htmlspecialchars($_GET['login']);
		}
		if ($login=="failure"){
			echo "<center><p class='error'> Sorry ! We are unable to create your account</p></center>";
		}
	?>

  <div class="form">
	<form method="POST" action="signup_process.php">
		<label class="l">YOUR NAME</label><br>
		<input type="text" name="fname" placeholder="Enter your name...." autocomplete="off" value="<?php if(isset($_GET['name']) ){echo($_GET['name']);}  ?>"><br>
		<label class="l">E-MAIL ID</label><br>
		<input type="text" name="email" placeholder="Enter your email...." autocomplete="off" value="<?php if(isset($_GET['email']) ){echo($_GET['email']);}  ?>"><br>
		<label class="l">MOBILE NO</label><br>
		<input type="text" name="mobno" placeholder="Enter your mobile number...." autocomplete="off" value="<?php if(isset($_GET['mobno']) ){echo($_GET['mobno']);}  ?>"><br>
		<label class="l">PASSWORD</label><br>
		<input type="Password" name="pass" placeholder="Password must be atleast six character..."><br>
		<label class="l">RE-ENTER THE PASSWORD</label><br>
		<input type="Password" name="passcon" placeholder="Re enter your password..."><br>
		<input type="submit" name="submit" value="CREATE AN ACCOUNT"><br>
	</form>
	<a href="signin.php">Already a member?Sign In</a>
	
</div>
</body>
</html>