<footer style="background-color: #c9cdd3;">
	<div style="display: inline-block;float: left;background-color: #c9cdd3;margin: 20px;height: 70px;">
		<h4 style="display: inline-block;">Contact US:</h4>
		<i style="display: inline-block;margin: 10px;background-color: #93969b;padding: 20px;border-radius: 20cm;" class="fab fa-facebook-f fa-x"></i>
		<i style="display: inline-block;margin: 10px;background-color: #93969b;padding: 20px;border-radius: 20cm;" class="fas fa-envelope fa-x"></i>
		<i style="display: inline-block;margin: 10px;background-color: #93969b;padding: 20px;border-radius: 20cm;" class="fab fa-youtube fa-x"></i>
		<i style="display: inline-block;margin: 10px;background-color: #93969b;padding: 20px;border-radius: 20cm;" class="fab fa-twitter fa-x"></i>
	</div>

	<div style="display: inline-block;margin:20px;background-color: #c9cdd3;height: 70px;">
		<form action="" method="POST" id="news">
			<h3 style="display: inline-block;float: left;">Subscribe to Newsletter:</h3>
			<input type="email" name="email" autocomplete="off" placeholder="Enter your email" style="display: inline-block;height: 40px;width: 400px;float: left;font-size: 1.3em;padding: 0 10px 0 10px;">
			<button type="submit" name="newsbtn" form="news" style="height: 45px;float: left;"><i class="fab fa-telegram-plane fa-2x"></i></button>
		</form>
	</div>
</footer>

<?php
	require_once 'includes/dbConn.php';
	if (isset($_POST['newsbtn'])) {
		$email=htmlspecialchars($_POST['email']);
		trim($email);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {?>
			<script type="text/javascript">alert("Invalid e-mail address !");</script>
<?php	}
		else{
			$sql="SELECT * FROM newsletter WHERE Email='$email'";
			$result=mysqli_query($conn,$sql);
			$resultCheck=mysqli_num_rows($result);
			if ($resultCheck>0) {?>
				<script type="text/javascript">alert("You are already subscribed to our newsletter");</script>
				<?php	
			}
			else{
				$sql="INSERT INTO newsletter (Email) VALUES ('$email')";
				if (mysqli_query($conn,$sql)) {?>
					<script type="text/javascript">alert("You are successfully subscribed to our newsletter");</script>
					<?php	
				}
			}	
		}	
	}
?>