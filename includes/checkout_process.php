<?php
session_start();
if (isset($_SESSION['uname']) && isset($_SESSION['pass']) && isset($_SESSION['email']) && isset($_SESSION['mobno']) && isset($_SESSION['utype']) && isset($_POST['submit'])) {
	require_once("dbConn.php");
	if (isset($_POST['address'])) {
		$total=htmlspecialchars($_POST['total']);
		$user=htmlspecialchars($_SESSION['email']);
		$payment=htmlspecialchars($_POST['pay']);
		$addressid=htmlspecialchars($_POST['address']);
		date_default_timezone_set("Asia/Kolkata");
		$date=date('Y-m-d');
		$time=date('H:i:s');
		$status="placed";
		$sql="INSERT INTO orders (Total,Address,Payment,Customer_ID, Order_Date, Order_Time, Order_Status) VALUES ('$total','$addressid','$payment','$user','$date','$time','$status')";
		if (!mysqli_query($conn,$sql)) {
			header("Location:../checkout.php?error=db");
			exit();
		}		
		else{
			$sql="SELECT * FROM orders ORDER BY ID DESC LIMIT 1";
			$result=mysqli_query($conn,$sql);
			while ($row=mysqli_fetch_assoc($result)) {
				$orderid=$row['ID'];
			}

			$sql="SELECT * FROM cart WHERE U_ID='$user' AND Purchased='0'";
			$result=mysqli_query($conn,$sql);
			$success=0;
			while ($row=mysqli_fetch_assoc($result)) {
				$dbpid=$row['P_ID'];
				$dbqty=$row['Quantity'];
				$dbsize=$row['Size'];
				$sql="INSERT INTO orderproducts (Order_ID,P_ID,Username,Quantity,Size) VALUES ('$orderid','$dbpid','$user','$dbqty','$dbsize')";
				if (mysqli_query($conn,$sql)) {
					$sql2="SELECT * FROM productdetails WHERE P_ID='$dbpid'";
					$result2=mysqli_query($conn,$sql2);
					while ($prow=mysqli_fetch_assoc($result2)) {
						$s=$dbsize."QTY";
						if ($s=="XSQTY") {
							$qty=$prow['XSQTY'];
						}
						else if ($s=="SQTY") {
							$qty=$prow['SQTY'];
						}
						else if ($s=="MQTY") {
							$qty=$prow['MQTY'];
						}
						else if ($s=="LQTY") {
							$qty=$prow['LQTY'];
						}
						else if ($s=="XLQTY") {
							$qty=$prow['XLQTY'];
						}
						$qty=$qty-$dbqty;
						if ($qty<0) {
							header("Location:../addtocart.php?q=d&id=$dbpid&size=$dbsize&err=qty");
							exit();
						}
						$query="UPDATE productdetails SET $s='$qty' WHERE P_ID='$dbpid'";
						if (mysqli_query($conn,$query)) {
							$success=1;
						}
						else{
							header("Location:../checkout.php?error=update");
						}
					}
				}
				else{
					$success=0;
				}
			}

			if ($success=1) {
				$sql="UPDATE cart SET Purchased='1' WHERE U_ID='$user'";
				if (!mysqli_query($conn,$sql)) {
					header("Location:../checkout.php?error=db");
					exit();
				}
				else{
					if($payment=="cod"){
						?>
						<script type="text/javascript">
							window.location.replace("../account.php");
						</script>
						<?php
						$sql="UPDATE orders SET Order_Status='confirmed' WHERE ID='$orderid'";
						mysqli_query($conn, $sql);
						$header = "MIME-Version: 1.0" . "\r\n";
						$header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						$header='From: order-update@techsajjad.tk' . "\r\n" .'Reply-To: info@techsajjad.tk';
						$subject="Your order #".$orderid." is confirmed";
						$name1=$_SESSION['uname'];
						$message="Hello $name1 ,"."\n"."
					  Your order has been successfully placed and we will send you a confirmation email once it is shipped.";
						mail($user, $subject, $message,$header);
						exit();
					}
					else if ($payment=="card" || $payment=="net") {
						header("Location:../instamojo.php?amount=$total&id=$orderid");
					}
				}	
			}
			else if ($success=0) {
				header("Location:../checkout.php");
				exit();
			}
		}	
	}
	else{
	
		$user=htmlspecialchars($_SESSION['email']);
		$name=htmlspecialchars($_POST['name']);
		$mobno=htmlspecialchars($_POST['mobno']);
		$house=htmlspecialchars($_POST['house']);
		$street=htmlspecialchars($_POST['street']);
		$city=htmlspecialchars($_POST['city']);
		$state=htmlspecialchars($_POST['state']);
		$pincode=htmlspecialchars($_POST['pincode']);
		$total=htmlspecialchars($_POST['total']);


		if (empty($user) || empty($name) || empty($mobno) || empty($house) || empty($street) || empty($city) || empty($state) || empty($pincode) || empty($total)) {
			header("Location:../checkout.php?error=empty");
			exit();
		}
		else{
			if (!preg_match("/^[A-Za-z ]*$/", $name) || !preg_match("/^[0-9]*$/", $mobno) || strlen($mobno)!=10 || !preg_match("/^[A-Za-z0-9 -,.]*$/", $house) || !preg_match("/^[A-Za-z ,.]*$/", $street) || !preg_match("/^[A-Za-z ]*$/", $state) || !preg_match("/^[0-9]*$/", $pincode)) {
				
				header("Location:../checkout.php?error=pattern");
				exit();
			}
			else{
				$payment=htmlspecialchars($_POST['pay']);
				$addressid=uniqid("",true);
				date_default_timezone_set("Asia/Kolkata");
				$date=date('Y-m-d');
				$time=date('H:i:s');
				$sql="INSERT INTO orders (Total,Address,Payment,Customer_ID, Order_Date, Order_Time,Order_Status) VALUES ('$total','$addressid','$payment','$user','$date','$time','placed')";
				if (!mysqli_query($conn,$sql)) {
					header("Location:../checkout.php?error=db");
					exit();
				}
				else{
					$sql="INSERT INTO address (ID, Username, Name, Mobno, House, Street, City, State, Pincode) VALUES ('$addressid','$user','$name','$mobno','$house','$street','$city','$state','$pincode')";
					if (!mysqli_query($conn,$sql)) {
						header("Location:../checkout.php?error=db");//error 
						exit();
					}
					else{
						$sql="SELECT * FROM orders ORDER BY ID DESC LIMIT 1";
						$result=mysqli_query($conn,$sql);
						while ($row=mysqli_fetch_assoc($result)) {
							$orderid=$row['ID'];
						}

						$sql="SELECT * FROM cart WHERE U_ID='$user' AND Purchased='0'";
						$result=mysqli_query($conn,$sql);
						$success=0;
						while ($row=mysqli_fetch_assoc($result)) {
							$dbpid=$row['P_ID'];
							$dbqty=$row['Quantity'];
							$dbsize=$row['Size'];
							$sql="INSERT INTO orderproducts (Order_ID,P_ID,Username,Quantity,Size) VALUES ('$orderid','$dbpid','$user','$dbqty','$dbsize')";
							if (mysqli_query($conn,$sql)) {
								$sql="SELECT * FROM productdetails WHERE P_ID='$dbpid'";
								$result=mysqli_query($conn,$sql);
								while ($prow=mysqli_fetch_assoc($result)) {
									$s=$dbsize."QTY";
									if ($s=="XSQTY") {
										$qty=$prow['XSQTY'];
									}
									else if ($s=="SQTY") {
										$qty=$prow['SQTY'];
									}
									else if ($s=="MQTY") {
										$qty=$prow['MQTY'];
									}
									else if ($s=="LQTY") {
										$qty=$prow['LQTY'];
									}
									else if ($s=="XLQTY") {
										$qty=$prow['XLQTY'];
									}
									$qty=$qty-$dbqty;
									$query="UPDATE productdetails SET $s='$qty' WHERE P_ID='$dbpid'";
									if (mysqli_query($conn,$query)) {
										$success=1;
									}
									else{
										header("Location:../checkout.php?error=update");
									}
								}
							}
							else{
								$success=0;
							}
						}
						if ($success=1) {
							$sql="UPDATE cart SET Purchased='1' WHERE U_ID='$user'";
							if (!mysqli_query($conn,$sql)) {
								header("Location:../checkout.php?error=db");
								exit();
							}
							else{
								if($payment=="cod"){
									?>
									<script type="text/javascript">
										window.location.replace("../account.php");
									</script>
									<?php
									$sql="UPDATE orders SET Order_Status='confirmed' WHERE ID='$orderid'";
									mysqli_query($conn, $sql);
									$header = "MIME-Version: 1.0" . "\r\n";
									$header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
									$header='From: order-update@techsajjad.tk' . "\r\n" .'Reply-To: info@techsajjad.tk';
									$subject="Your order #".$orderid." is confirmed";
									$name1=$_SESSION['uname'];
									$message="Hello $name1 ,"."\n"."Your order has been successfully placed and we will send you a confirmation email once it is shipped.";
									mail($user, $subject, $message,$header);
									exit();
								}
								else if ($payment=="card" || $payment=="net") {
									header("Location:../instamojo.php?amount=$total&id=$orderid");
								}
							}	
						}
						else if ($success=0) {
							header("Location:../checkout.php");
							exit();
						}
					}	
				}
			}	
		}
	}
}
else{
	header("Location:../home.php");
	exit();
}

?>	