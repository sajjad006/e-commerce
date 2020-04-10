<?php
include_once('includes/dbConn.php');
session_start();
if (isset($_POST['submit'])) {
	
	$username=htmlspecialchars($_POST['fname']);
	$email=htmlspecialchars($_POST['email']);
	$mobno=htmlspecialchars($_POST['mobno']);
	$password=htmlspecialchars($_POST['pass']);
	$passcon=htmlspecialchars($_POST['passcon']);
	$utype=htmlspecialchars("user");

	if (empty($username) || empty($email) || empty($mobno) || empty($password) || empty($passcon)) {
		header("Location:signup.php?err=empty&name=$name&email=$email&mobno=$mobno");
		exit();
	}
	else{
		if (!preg_match("/^[A-Za-z ]*$/", $username)) {
			header("Location:signup.php?err=name&email=$email&mobno=$mobno");
			exit(); 
		}
		else{
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				header("Location:signup.php?err=email&name=$username&mobno=$mobno");
				exit(); 
			}
			else{
				if (!preg_match("/^[0-9]*$/", $mobno) || strlen($mobno)!=10) {
					header("Location:signup.php?err=mob&name=$username&email=$email");
					exit();
				}
				else{
					if (strlen($password)<6 || !preg_match("/^[A-za-z!@#$%^&*0-9]*$/", $password)) {
						header("Location:signup.php?err=pass&name=$username&email=$email&mobno=$mobno");
						exit();
					}
					else{
						if ($password!==$passcon) {
							header("Location:signup.php?err=passmis&name=$username&email=$email&mobno=$mobno");
							exit();
						}
						else{
							$sql = "SELECT * FROM userinfo WHERE Email='$email'";
							$result=mysqli_query($conn, $sql);
							$resultCheck=mysqli_num_rows($result);
							if ($resultCheck>0) {
								header("Location:signup.php?err=usertaken&name=$username&mobno=$mobno");
								exit();
							}
							else{
								$sql="SELECT * FROM userinfo WHERE Mobno='$mobno'";
								$result=mysqli_query($conn,$sql);
								$resultCheck=mysqli_num_rows($result);
								if ($resultCheck>0) {
									header("Location:signup.php?err=mobtaken&name=$username&email=$email");
									exit();
							
								}
								else{
									$hashedpwd=password_hash($password, PASSWORD_DEFAULT);
									if($stmt=$conn->prepare("INSERT INTO userinfo (Username,Email,Mobno,Password,Usertype) VALUES (?,?,?,?,?)")){
										$stmt->bind_param("sssss", $username, $email, $mobno, $hashedpwd, $utype);
									 	$stmt->execute();
										$stmt->close();
										$_SESSION['uname']=$username;
										$_SESSION['pass']=$password;
										$_SESSION['email']=$email;
										$_SESSION['mobno']=$mobno;
										$_SESSION['utype']=$utype;
										header("Location:home.php");
										exit();
									}
									else{
										header("Location:signup.php?login=failure");
									}
								}	
							}
						}
					}
				}
			}
		}
	}
}
else{
	header("Location:signup.php");
	exit();
}
mysqli_close($conn);

?>