<!DOCTYPE html>
<html>
<head>
	<title>Sign In</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style/homestyle.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="style/style.css">
</head>
<body>
	<?php require_once 'includes/usernav.php'; ?>
	<center><h1>SIGN IN </h1></center> 
	
	<div class="form">
		<?php
		$error="";
		if (isset($_GET['error'])) {
			$error=htmlspecialchars($_GET['error']);
		}
		
		if ($error=="empty") {
			echo '<b><p style="margin-left:150px;color:red;">Please fill in all the fields</p></b>';
		}
		else if($error=="invalid"){
			echo '<b><p style="margin-left:150px;color:red;">Invalid username or password</p></b>';
		}
		if(isset($_GET['reset'])){
            if($_GET['reset']=='success'){
                echo '<b><p style="margin-left:150px;color:red;">Successfully changed your password</p></b>';    
            }
            else if($_GET['reset']=="failure"){
                echo '<b><p style="margin-left:150px;color:red;">Sorry couldnot change your password! Plese try again.</p></b>';
            }
        }
		?>
		<form action="signin_process.php" method="post">
			<label class="l">Email-id or Mobile Number:</label><br>
			<input type="text" name="email" autocomplete="off"><br>
			<label class="l">Password:</label><br>
			<input type="Password" name="pwd"><br>
			<input type="hidden" name="url" value="<?php echo $_SERVER['HTTP_REFERER']; ?>">
			<input type="submit" name="submit" value="submit">		
		</form>
		<a href="signup.php">Not yet a member? Sign in</a>
		<a href="forgot-password.php" style="margin-left: 100px;">Forgot Password?</a>
	</div>
</body>
</html>