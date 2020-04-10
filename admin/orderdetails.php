<?php
	session_start();
	if (isset($_SESSION['uname']) && isset($_SESSION['pass']) && isset($_SESSION['email']) && isset($_SESSION['mobno']) && isset($_SESSION['utype']) && $_SESSION['utype']=="admin"){
		$cc='';$awb='';
		$uname=$_SESSION['email'];
		require_once 'includes/dbConn.php';
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<title>Order Details</title>
			<link rel="stylesheet" type="text/css" href="includes/style.css">
			<link rel="stylesheet" type="text/css" href="includes/admin_style.css">
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<script type="text/javascript" src="includes/adminscript.js"></script>
		</head>
		<body>

<?php
	require_once 'includes/adminnav.php';
		if (!empty($_GET['id'])) {
			$orderid=htmlspecialchars($_GET['id']);	
		}
		else{
			header("Location:orders.php");
			exit();
		}
		
		require_once 'includes/dbConn.php';
		$sql="SELECT orders.*, address.Name,address.Mobno,address.House,address.Street,address.City,address.State,address.Pincode FROM orders INNER JOIN address ON orders.Address=address.ID WHERE orders.ID='$orderid'";
		$result=mysqli_query($conn,$sql);
		if (!$result) {
			header("Location:orders.php");
			exit();
		}
		
		else{
			while ($row=mysqli_fetch_assoc($result)) {
				$dbuname=htmlspecialchars($row['Customer_ID']);
				$orderdate=$row['Order_Date'];$ordertime=$row['Order_Time'];$total=$row['Total'];$addresid=$row['Address'];$payment=$row['Payment'];
				$name=$row['Name'];$mobno=$row['Mobno'];$house=$row['House'];$street=$row['Street'];$city=$row['City'];$state=$row['State'];$pincode=$row['Pincode'];$orderstatus=$row['Order_Status'];
			}
			?>
			<br><h1>ORDER DETAILS:</h1><hr><br><br>
			<?php
				if ($orderstatus=="placed") {?>
					<center><form action="" method="POST" id="f1">
						<button type="submit" name="confirm" class="a" style="background-color: green;color: white;width: 200px;border-radius: 0;"><i class="fa fa-check fa-2x" aria-hidden="true"></i>CONFIRM</button>
						<button type="submit" name="reject" class="a" style="background-color:red;color: white;width: 200px;border-radius: 0;"><i class="fa fa-times fa-2x" aria-hidden="true"></i>REJECT</button>
					</form></center>
	<?php		}
				else if($orderstatus=="confirmed"){?>
					<center><form action="" method="POST">
						<input type="text" name="cc" placeholder="Courier Company..." style="width: 300px;border-radius: 0;">
						<input type="text" name="awb" placeholder="AWB Number..." style="width: 300px;border-radius: 0;">
						<button type="submit" class="a" name="ship" style="background-color: green;color: white;width: 200px;border-radius: 0;"><i class="fa fa-plane fa-2x" aria-hidden="true"></i>SHIP ORDER</button>
					</form></center>
	<?php 		}
				else if ($orderstatus=="shipped") {?>
					<center>
						<form action="" method="POST">
							<button type="submit" class="a" name="deliver" style="background-color: ;color: white;width: 200px;border-radius: 0;"><i class="fa fa-archive fa-2x" aria-hidden="true"></i>DELIVER ORDER</button>
						</form>
					</center>
	<?php		}
				
				if (isset($_POST['confirm'])) {
					$sql="UPDATE orders SET Order_Status='confirmed' WHERE ID='$orderid'";
					mysqli_query($conn,$sql)?>
					<script type="text/javascript">window.location.replace("orders.php");</script>
	<?php			exit();
				}
				else if (isset($_POST['reject'])) {
					$sql="UPDATE orders SET Order_Status='rejected' WHERE ID='$orderid'";
					mysqli_query($conn,$sql);?>
					<script type="text/javascript">window.location.replace("orders.php");</script>
	<?php			exit();
				}
				else if(isset($_POST['ship'])){
					$cc=htmlspecialchars($_POST['cc']);
					$awb=htmlspecialchars($_POST['awb']);
					if (!preg_match("/^['A-Za-z ']*$/", $cc) && !preg_match("/^[A-Za-z 0-9]*$/", $awb)) {
						header("Location:orderdetails.php?id=$orderid");
						exit();
					}
					else{
						$header='From: order-update@techsajjad.tk' . "\r\n" .'Reply-To: info@techsajjad.tk';
						$subject="Your order #".$orderid." is shipped";
						$message="Hello $name ,"."\n"."Your order has been shipped with $cc and AWB Number: $awb and you can track the same with the AWB Number. You'll be notified once it is delivered.";
						mail($dbuname, $subject, $message,$header);
						$sql="UPDATE orders SET Order_Status='shipped' WHERE ID='$orderid'";
						if (mysqli_query($conn,$sql)) {
							$sql2="UPDATE orders SET Courier='$cc',AWB='$awb' WHERE ID='$orderid'";
							if (mysqli_query($conn,$sql2)) {?>
								<script type="text/javascript">window.location.replace("orders.php");</script>
	<?php						exit();
							}
							else{?>
								<script type="text/javascript">window.location.replace("orders.php");</script>
	<?php						exit();
							}
						}
						else{
							header("Location:orderdetails.php?error=true");
							exit();
						}
					}
				}
				else if (isset($_POST['deliver'])) {
					$header='From: order-update@techsajjad.tk' . "\r\n" .'Reply-To: info@techsajjad.tk';
					$subject="Your order #".$orderid." is delivered";
					$message="Hello $name ,"."\n"."Your order has been successfully delivered Thank you for using our e-commerce system. Hope to serve you better in the future.";
					mail($dbuname, $subject, $message,$header);
					$sql="UPDATE orders SET Order_Status='delivered' WHERE ID='$orderid'";
					if (mysqli_query($conn,$sql)) {?>
						<script type="text/javascript">window.location.replace("orders.php");</script>
	<?php				exit();
					}
					else{?>
						<script type="text/javascript">window.location.replace("orderdetails.php");</script>
	<?php				exit();
					}
				}
			?>			
			<div style="background-color: #c8cbd1;margin: 40px;border-radius: 10px;">
				<h2 style="display: inline-block;margin-right: 40px;">Order Number:<?php echo $orderid; ?></h2>
				<h3 style="display: inline-block;margin: 20px; ">Order Status:<?php echo strtoupper($orderstatus); ?></h3>
				<h3 style="display: inline-block;margin: 20px;">Date:<?php echo $orderdate; ?></h3>  
				<h3 style="display: inline-block;margin: 20px;">Time:<?php echo $ordertime; ?></h3>
				<h3 style="display: inline-block;margin: 20px;">Order Total:  <?php echo $total; ?></h3>
				<h3 style="display: inline-block;margin: 20px;">Payment method: <?php echo $payment; ?></h3>
			</div>

			<div style="margin: 40px;border-radius: 10px;background-color: #c8cbd1">
				<h1>Address</h1>
				<h3 style="margin: 0;"><?php echo $name; ?></h3>
				<h3 style="margin: 0;"><?php echo $mobno; ?></h3>
				<h3 style="margin: 0;"><?php echo $house.",".$street; ?></h3>
				<h3 style="margin: 0;"><?php echo $city."-".$pincode; ?></h3>
				<h3 style="margin: 0;"><?php echo $state; ?></h3>
			</div>	
		<?php

		$sql="SELECT orderproducts.Order_ID, orderproducts.P_ID, orderproducts.Quantity, orderproducts.Size, productdetails.P_Name, productdetails.P_Category, productdetails.P_Brand, productdetails.P_Image, productdetails.P_Price FROM orderproducts INNER JOIN productdetails ON orderproducts.P_ID=productdetails.P_ID WHERE Order_ID='$orderid'";
		$result=mysqli_query($conn, $sql);
		while ($row=mysqli_fetch_assoc($result)) {
			$dst="includes/".$row['P_Image'];?>
			<div style="margin-left:40px;">
		  			<img src="<?php echo $dst; ?>" width="200px;" height="200px;" style="display: inline-block;margin-top: 20px;">
		  			<div style="display: inline-block;margin-left: 80px;">
		  				<h1><?php echo $row['P_Brand']."   ".$row['P_Name']; ?></h1>
		  				<h2>Rs. <?php echo $row['P_Price']; ?></h2>
		  				<h2>Size: <?php echo $row['Size']; ?></h2>
		  				<h2>Quantity:<?php echo $row['Quantity']; ?></h2>
		  			</div><hr style="margin-left: 0;">
		  	</div>
			<?php
			}
		}
	}	
	else{
		header("Location:../signin.php");
		exit();
	}
?>

		</body>
		</html>