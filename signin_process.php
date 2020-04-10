<?php

session_start();
	if (isset($_POST['submit'])) {
		include_once("includes/dbConn.php");
		$uinput=htmlspecialchars($_POST['email']);
		$password=htmlspecialchars($_POST['pwd']);
		if (empty($uinput) || empty($password)) {
			header("Location:signin.php?error=empty");
			exit();
		}
		else{
			$hashPwd="";$username="";$mobno="";$email="";$usertype="";
			$sql="SELECT * FROM userinfo WHERE Email='$uinput' OR Mobno='$uinput'";
			$result=mysqli_query($conn, $sql);
			$resultCheck=mysqli_num_rows($result);
			if ($resultCheck>0) {
				$row=mysqli_fetch_assoc($result); 
				$hashPwd=$row['Password'];
				$username=$row['Username'];
				$mobno=$row['Mobno'];
				$email=$row['Email'];
				$usertype=$row['Usertype'];
				
				$passCheck=password_verify($password,$hashPwd);
				if ($passCheck==false) {
					header("Location:signin.php?error=invalid");
					exit();
				}
				else if ($passCheck==true) {
					$_SESSION['uname']=htmlspecialchars($username);
					$_SESSION['pass']=htmlspecialchars($password);
					$_SESSION['email']=htmlspecialchars($email);
					$_SESSION['mobno']=htmlspecialchars($mobno);
					$_SESSION['utype']=htmlspecialchars($usertype);
					if ($usertype=='user') {
						$url=$_POST['url'];
						if (strpos($url, "signin")) {
							header("Location: home.php");
							exit();
						}
						else{
							header("Location: ".$url);
							exit();	
						}
					}
					elseif ($usertype=='admin') {
						header('Location:admin/admin.php');
						exit();
					}
					else{
						header('Location:signin.php?error=invalid');
					}
				}
			}
			else{
				header("Location:signin.php?error=invalid");
				exit();
			}
		}
	}
	else{
		header("Location:signin.php");
		exit();
	}
?>