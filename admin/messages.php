<?php
session_start();
if (isset($_SESSION['uname']) && isset($_SESSION['pass']) && isset($_SESSION['email'])&& isset($_SESSION['mobno']) && isset($_SESSION['utype']) && $_SESSION['utype']=="admin"){
	require_once 'includes/dbConn.php';
?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>MESSAGES</title>
		<link rel="stylesheet" type="text/css" href="includes/style.css">
		<link rel="stylesheet" type="text/css" href="includes/admin_style.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
		<?php require_once 'includes/adminnav.php'; ?>
		<center><div style="margin: 40px;">
			<button type="button" class="a" onclick="msgfunc()">COMPOSE MESSAGES</button>
			<button type="button" class="a" onclick="msg()">VIEW MESSAGES</button>
		</div></center>

		<div id="compose" style="display: none;">
			<form id="composemsg" action="includes/message_process.php" method="POST">
				
				<label style="display: inline-block;width: 150px;"><h2>Recepient:</h2></label>
				<select name="to" form="composemsg" style="border-radius: 0;display: inline-block;">
					<option>NEWSLETTER</option>
					<option>ADMIN</option>
					<option>USERS</option>
				</select><br>

				<label style="display: inline-block;width: 150px;"><h2>Subject:</h2></label>
				<input type="text" name="subject" placeholder="Subject of the message...." style="display: inline-block;border-radius: 0;"><br>

				<label style="display: inline-block;width: 150px;"><h2>Body:</h2></label>
				<textarea name="body" form="composemsg" placeholder="Body of the message...." cols="70" rows="7"></textarea><br>

				<input type="hidden" name="sender" value="<?php echo $_SESSION['email']; ?>">

				<button type="submit" name="composesubmit" form="composemsg" class="a" style="margin: 40px;margin-left: 300px;"><i class="fa fa-paper-plane-o fa-2x" aria-hidden="true"></i>  SEND MESSAGE</button>
			</form>
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

	</body>
	</html>
	<?php
}
else{
	header("Location:../signin.php");
	exit();
}