<?php
	require_once 'includes/dbConn.php';
	if (isset($_GET['send'])) {
		$send=htmlspecialchars($_GET['send']);
		if ($send=="success") {?>
			<script type="text/javascript">document.getElementById("error").innerHTML="Successfully sent your message.";</script>
<?php	}
		else if ($send=="failure") {?>
			<script type="text/javascript">document.getElementById("error").innerHTML="Sorry couldn't send your message.";</script>
<?php	}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>MESSAGES</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="style/homestyle.css">
	<script type="text/javascript">
		function msgfunc() {
			document.getElementById('compose').style.display="block";
			document.getElementById('view').style.display="none";
		}
		function msg() {
			document.getElementById('view').style.display="block";
			document.getElementById('compose').style.display="none";
		}
	</script>
</head>
<body>
	<?php require_once 'includes/usernav.php'; ?>
	<center><div style="margin: 40px;">
		<button type="button" class="a" onclick="msgfunc()">COMPOSE MESSAGES</button>
		<!--<button type="button" class="a" onclick="msg()">VIEW MESSAGES</button>-->
		<p id="error"></p>
	</div></center>

	<div id="compose" style="display: none;">
		<form id="composemsg" action="" method="POST">
			<label style="display: inline-block;width: 150px;"><h2>Your Name:</h2></label>
			<input style="display: inline-block;border-radius: 0;" type="text" name="name" placeholder="Enter your name..." required><br>

			<label style="display: inline-block;width: 150px;s"><h2>Email id:</h2></label>
			<input style="display: inline-block;height: 40px;width: 500px;" type="email" name="email" placeholder="Enter your email...." required><br>

			<label style="display: inline-block;width: 150px;"><h2>Subject:</h2></label>
			<input type="text" name="subject" placeholder="Subject of the message...." style="display: inline-block;border-radius: 0;" required><br>

			<label style="display: inline-block;width: 150px;"><h2>Body:</h2></label>
			<textarea name="body" form="composemsg" placeholder="Body of the message...." cols="70" rows="7" required></textarea><br>

			<button type="submit" name="issuesubmit" form="composemsg" class="a" style="margin: 40px;margin-left: 300px;"><i class="fa fa-paper-plane-o fa-2x" aria-hidden="true"></i>  SEND MESSAGE</button>
		</form>

		<?php
			if (isset($_POST['issuesubmit'])) {
				$name=htmlspecialchars($_POST['name']);
				$email=htmlspecialchars($_POST['email']);
				$subject=htmlspecialchars($_POST['subject']);
				$body=htmlspecialchars($_POST['body']);
				if (!preg_match("/^[A-Za-z ]*$/", $name)) {
					header("Location:home.php?error=name");
					exit();
				}
				else{
				    $header='From: info@techsajjad.tk' . "\r\n" .'Reply-To: '.$email;
					if(mail("sajjad@techsajjad.tk", $subject, $body, $header)){?>
	            		<script type="text/javascript">document.getElementById("error").innerHTML="Successfully sent your message.";</script>
            <?php
					}
					else{?>
            			<script type="text/javascript">document.getElementById("error").innerHTML="Sorry couldn't send you message.";</script>
<?php
					}
				}
			}
		?>
	</div>

	<div id="view" style="display: none;">
		<?php
			$sql="SELECT * FROM messages";
			$result=mysqli_query($conn,$sql);
			$resultCheck=mysqli_num_rows($result);
			if ($resultCheck<=0) {
				echo "<h1>You have no messages</h1>";
				exit();
			}
			else{
				while ($row=mysqli_fetch_assoc($result)) {?>
					<div style="margin: 20px;border:1px solid black;background-color: white;">
						<h2>Sender: <?php echo $row['Sender']; ?></h2>
						<h2>Sent To:<?php echo $row['Recepient']; ?></h2>
						<h2>Subject: <?php echo $row['Subject']; ?></h2>
						<h2>Body: <?php echo $row['Body']; ?></h2>
					</div>
<?php			}
			}
?>
	</div>
<?php require_once 'includes/footer.php'; ?>
</body>
</html>