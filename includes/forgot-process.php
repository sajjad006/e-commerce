<?php
	if (isset($_POST['submit'])) {
		function generateRandomString($length) {
		    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $charactersLength = strlen($characters);
		    $randomString = '';
		    for ($i = 0; $i < $length; $i++) {
		        $randomString .= $characters[rand(0, $charactersLength - 1)];
		    }
		    return $randomString;
		}

		require_once 'dbConn.php';
		$email=htmlspecialchars($_POST['email']);
		if (empty($email)) {
			header("Location:../forgot-password.php?error=email");
			exit();
		}
		else{
			$sql="SELECT * FROM userinfo WHERE Email='$email'";
			$result=mysqli_query($conn, $sql);
			if (mysqli_num_rows($result)<=0) {
				header("Location:../forgot-password.php?error=account");
				exit();
			}
			else{
				$selector=bin2hex(generateRandomString(8));
				$validator=generateRandomString(32);
				$url="www.techsajjad.tk/create-new-password.php?selector=$selector&validator=".bin2hex($validator);
				$expiry=date("U")+1800;
				$sql="DELETE FROM resetpassword WHERE Email='$email'";
				mysqli_query($conn, $sql);
				$hashedValidator=password_hash($validator, PASSWORD_DEFAULT);
				$sql="INSERT INTO resetpassword (Validator, Selector, ExpiryTime, Email) VALUES ('$hashedValidator','$selector','$expiry','$email')";
				if (!mysqli_query($conn, $sql)) {
					header("Location:../forgot-password.php?error=db");
					exit();
				}	
				else{
					$to=$email;
					$subject="Reset your password for techsajjad";
					$message="<p>We received a request to reset your password. The link to reset your password is below. If you did not make this request then please ignore the message.</p>";
					$message .= "<p>Here is your password reset link:</p>";
					$message .= '<a href="'.$url.'">Click here to reset your password.</a>';
					$message .= '<p>Button not working?  Copy the folling link and paste it in your browser:</p>';
					$message .= '<a href="'.$url.'">'.$url.'</a>';
					$headers  = "From: account@techsajjad.tk\r\n";
					$headers .= "Reply-To: info@techsajjad.tk\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

					mail($to, $subject, $message, $headers);
					header("Location:../forgot-password.php?reset=success");
				}
			}
		}	
	}
	else{
		header("Location;../forgotpassword.php");
		exit();
	}
?>