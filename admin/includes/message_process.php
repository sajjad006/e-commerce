<?php
	require_once 'dbConn.php';
	$success=0;
	if (isset($_POST['composesubmit'])) {
		$recepient=htmlspecialchars($_POST['to']);
		$subject=htmlspecialchars($_POST['subject']);
		$body=htmlspecialchars($_POST['body']);
		$sender=$_POST['sender'];
		$header='From: info@techsajjad.tk' . "\r\n" .'Reply-To: info@techsajjad.tk';
		$sql="INSERT INTO messages (Sender,Recepient,Subject,Body) VALUES ('$sender','$recepient','$subject','$body')";
		if (mysqli_query($conn,$sql)) {
			if ($recepient=="NEWSLETTER") {
				$sql2="SELECT * FROM newsletter";
			}
			else if ($recepient=="ADMIN") {
				$sql2="SELECT * FROM userinfo WHERE Usertype='admin'";
			}	
			else if ($recepient=="USERS") {
				$sql2="SELECT * FROM userinfo WHERE Usertype='user'";
			}
			$result2=mysqli_query($conn, $sql2);
			while ($row=mysqli_fetch_assoc($result2)) {
				if (mail($row['Email'], $subject, $body, $header)) {
					$success=1;
				}
				else{
					header("Location:../messages.php?add=failure");
					exit();
				}
			}
			if ($success==1) {
				header("Location:../messages.php?add=success");
				exit();
			}
		}
		else{
			header("Location:../messages.php?send=failure");
			exit();
		}
	}
?>