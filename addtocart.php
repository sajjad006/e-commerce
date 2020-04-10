<?php

	session_start();
	$type="";$id="";$uri="";
	$id=$_GET['id'];

	if (isset($_SESSION['uname']) && isset($_SESSION['pass']) && isset($_SESSION['email']) && isset($_SESSION['mobno']) && isset($_SESSION['utype']) ) {	
		require_once("includes/dbConn.php");
		$uname=htmlspecialchars($_SESSION['email']);
		if (empty($_POST['qty'])) {
			$uqty=1;
		}
		else{
			$uqty=htmlspecialchars($_POST['qty']);
		}
		$q=htmlspecialchars($_GET['q']);
		if ($q=="a") {
			$size=htmlspecialchars($_POST['size']);
			$uri=htmlspecialchars($_GET['uri']);
			if (isset($_GET['type'])) {
				$type=htmlspecialchars($_GET['type']);
			}

			if ($uri=="det") {
				$uri="productdetails.php?id=$id";
			}
			else if($uri="disp"){
				$uri="productdisplay.php?type=$type";
			}

			$sql="SELECT * FROM cart WHERE U_ID='$uname' AND P_ID='$id' AND Size='$size'";
			$result=mysqli_query($conn, $sql);
			$resultCheck=mysqli_num_rows($result);
			$query="SELECT * FROM productdetails WHERE P_ID='$id'";
			$check=mysqli_query($conn,$query);
			while ($row=mysqli_fetch_assoc($check)) {
				if ($size=="XS") {
					$qty2=$row['XSQTY'];
				}
				else if ($size=="S") {
					$qty2=$row['SQTY'];
				}
				else if ($size=="M") {
					$qty2=$row['MQTY'];
				}
				else if ($size=="L") {
					$qty2=$row['LQTY'];
				}
				else if ($size=="XL") {
					$qty2=$row['XLQTY'];
				}	
			}
			if ($qty2>=$uqty) {
			
				if ($resultCheck>0) {
					while ($row=mysqli_fetch_assoc($result)) {
						$qty=$row['Quantity'];
						if ($qty+$uqty>$qty2) {
							header("Location:$uri&error=qty");
							exit();
						}
						$purchased=$row['Purchased'];
					}
					if ($purchased==1) {
						$sql="UPDATE cart SET Purchased='0', Quantity='1' WHERE U_ID='$uname' AND P_ID='$id' AND Size='$size'";
					}
					else{
						$qty=$qty+$uqty;
						$sql="UPDATE cart SET Quantity='$qty' WHERE U_ID='$uname' AND P_ID='$id' AND Size='$size'";
					}
				}
				else{
					$sql="INSERT INTO cart (U_ID, P_ID, Purchased, Quantity, Size) VALUES ('$uname','$id','0','$uqty','$size')";
				}

				if (mysqli_query($conn,$sql)) {
						header("Location:$uri&add=success");
						exit();			
				}
				else{
					header("Location:$uri&add=failure");
					exit();
				}
			}
			else{
				header("Location:$uri&error=qty");
				exit();
			}	
		}
		elseif ($q=="d") {
			$size=htmlspecialchars($_GET['size']);
			$sql="DELETE FROM cart WHERE U_ID='$uname' AND P_ID='$id' AND Size='$size'";
			if(mysqli_query($conn, $sql)){
				if (isset($_GET['err'])) {
					$e=htmlspecialchars($_GET['err']);
					if ($e=="qty") {
						header("Location:cart.php?err=qty");
						exit();
					}
				}
				else{
					header("Location:cart.php");
					exit();
				}
			}
			else{
				header("Location:home.php");
			}
			$e="";
			

		}
	}
	else{
		header("Location:signin.php");
		exit();
	}
?>