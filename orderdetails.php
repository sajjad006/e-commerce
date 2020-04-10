<?php
	session_start();
	if (isset($_SESSION['uname']) && isset($_SESSION['pass']) && isset($_SESSION['email']) && isset($_SESSION['mobno']) && isset($_SESSION['utype']) && $_SESSION['utype']=="user"){
		$uname=$_SESSION['email'];
		require_once 'includes/dbConn.php';
		?>
		<!DOCTYPE html>
		<html>
		<head>
			<title>Order Details</title>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
			<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" type="text/css" href="style/homestyle.css">
			<style type="text/css">
				.modal {
				  display: none; /* Hidden by default */
				  position: fixed; /* Stay in place */
				  z-index: 1; /* Sit on top */
				  left: 0;
				  top: 0;
				  width: 100%; /* Full width */
				  height: 100%; /* Full height */
				  overflow: auto; /* Enable scroll if needed */
				  background-color: rgb(0,0,0); /* Fallback color */
				  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
				  padding-top: 60px;
				}

				/* Modal Content/Box */
				.modal-content {
				  background-color: #fefefe;
				  margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
				  border: 1px solid #888;
				  width: 80%; /* Could be more or less, depending on screen size */
				  margin-left: 70px;
				}
			</style>
		</head>
		<body>
		
<?php
		require_once 'includes/usernav.php';
		if (isset($_GET['id'])) {
			$orderid=htmlspecialchars($_GET['id']);	
		}
		else{
			header("Location:account.php");
			exit();
		}
		
		require_once 'includes/dbConn.php';
		$sql="SELECT orders.*, address.Name,address.Mobno,address.House,address.Street,address.City,address.State,address.Pincode FROM orders INNER JOIN address ON orders.Address=address.ID WHERE orders.ID='$orderid'";
		$result=mysqli_query($conn,$sql);
		if (!$result) {
			header("Location:account.php");
			exit();
		}
		else{
		    $resultCheck=mysqli_num_rows($result);
		    if($resultCheck<=0){
		        header("Location:account.php");
		        exit();
		    }
			while ($row=mysqli_fetch_assoc($result)) {
				$dbuname=htmlspecialchars($row['Customer_ID']);
				if ($uname!==$dbuname) {
					header("Location:account.php");
					exit();
				}
				$orderdate=$row['Order_Date'];$ordertime=$row['Order_Time'];$total=$row['Total'];$addresid=$row['Address'];$payment=$row['Payment'];
				$name=$row['Name'];$mobno=$row['Mobno'];$house=$row['House'];$street=$row['Street'];$city=$row['City'];$state=$row['State'];$pincode=$row['Pincode'];$orderstatus=$row['Order_Status'];$cc=$row['Courier'];$awb=$row['AWB'];
			}
			?>
			<br><h1>ORDER DETAILS:</h1><hr><br><br>
			<div style="background-color: #c8cbd1;margin: 40px;border-radius: 10px;">
				<h2 style="display: inline-block;margin-right: 40px;">Order Number:<?php echo $orderid; ?></h2>
				<h3 style="display: inline-block;margin: 20px; ">Order Status:<?php echo strtoupper($orderstatus); ?></h3>
				<h3 style="display: inline-block;margin: 20px;">Date:<?php echo $orderdate; ?></h3>  
				<h3 style="display: inline-block;margin: 20px;">Time:<?php echo $ordertime; ?></h3>
				<h3 style="display: inline-block;margin: 20px;">Order Total:  <?php echo $total; ?></h3>
				<h3 style="display: inline-block;margin: 20px;">Payment method: <?php echo $payment; ?></h3>
				<?php
					$sql="SELECT * FROM returns WHERE Order_ID='$orderid'";
					$result=mysqli_query($conn, $sql);
					$resultCheck=mysqli_num_rows($result);
					if ($resultCheck > 0) {
						$row=mysqli_fetch_assoc($result);?>
						<h3 style="display: inline-block;margin: 20px;">Return Status: <?php echo $row['Status']; ?></h3>	
	<?php			}

					$now = time(); // or your date as well
					$your_date = strtotime($orderdate);
					$datediff = $now - $your_date;
					$days=round($datediff / (60 * 60 * 24));
					if ($days<=15 && $orderstatus=="delivered") {?>
						<div id="id02">
							<button class="a" onclick="document.getElementById('id01').style.display='block'" style="width:auto;">RETURN</button>
						</div>
						<div id="id01" class="modal">
						  <form class="modal-content animate" method="POST" id="returnform" action="includes/return.php" style="display: inline-block;">
						    <div class="container">
						      	<label for="uname"><b><h1>Return Reason</h1></b></label>
							    <select name="reason" form="returnform" style="width: 100%;padding: 12px 20px;margin: 8px 0;display: inline-block;border: 1px solid #ccc;box-sizing: border-box;font-size: 0.8em;" required>
							    	<option value="damaged"><h1>DAMAGED PRODUCT</h1></option>
								    <option value="notrequired"><h1>PRODUCT NOT REQUIRED</h1></option>
								    <option value="cheaper"><h1>FOUND CHEAPER ELSEWHERE</h1></option>
								    <option value="size"><h1>SIZE NOT APPROPIATE</h1></option>
								    <option value="wrong"><h1>WRONG PRODUCT DELIVERED</h1></option>
							    </select>
						    	<input type="hidden" name="orderid" value="<?php echo $orderid; ?>">
								<input type="hidden" name="payment" value="<?php echo $payment; ?>">
								<input type="hidden" name="total" value="<?php echo $total; ?>">
								<button type="submit" name="return" style="display: inline-block;margin: 20px;width: 200px;background-color: red;border-radius: 0;color: white;height: 50px;" form="returnform">RETURN</button>	

						    </div>
						  </form>
						</div>
						<script>
						// Get the modal
						var modal = document.getElementById('id01');

						// When the user clicks anywhere outside of the modal, close it
						window.onclick = function(event) {
						    if (event.target == modal) {
						        modal.style.display = "none";
						    }
						}
						</script>
	<?php						
					}
					if ($resultCheck>0) {?>
						<script type="text/javascript">document.getElementById('id02').style.display="none";</script>
	<?php			}

					if ($orderstatus=="confirmed") {?>
						<form method="POST" action="includes/return.php" id="cancelform" style="display: inline-block;">
							<input type="hidden" name="orderid" value="<?php echo $orderid; ?>">
							<input type="hidden" name="payment" value="<?php echo $payment; ?>">
							<input type="hidden" name="total" value="<?php echo $total; ?>">
							<button type="submit" form="cancelform" name="cancel" style="display: inline-block;margin: 20px;width: 200px;background-color: red;border-radius: 0;color: white;height: 50px;">CANCEL ORDER</button>
						</form>				
	<?php			}
				?>
				<?php
				if ($orderstatus=="shipped") {
					?>
					<h3 style="display: inline-block;margin: 20px;">Courier: <?php echo $cc; ?></h3>
					<h3 style="display: inline-block;margin: 20px;">AWB Number: <?php echo $awb; ?></h3>
	<?php		}
				?>
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
			$dst="admin/includes/".$row['P_Image'];?>
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
		if (isset($_GET['return'])) {
			$status=$_GET['return'];
			if ($status=="success") {?>
				<script type="text/javascript">alert("Successfully placed your return request!");</script>
<?php		}
			else if ($status=="failure") {?>
				<script type="text/javascript">alert("Sorry couldnot process your return request!");</script>
<?php		}
		}
		
		if (isset($_GET['cancel'])) {
			$status=$_GET['cancel'];
			if ($status=="success") {?>
				<script type="text/javascript">alert("Successfully cancelled your order!");</script>
<?php		}
			else if ($status=="failure") {?>
				<script type="text/javascript">alert("Sorry couldnot process your cancellation request!");</script>
<?php		}
		}
		require_once 'includes/footer.php';
	}	
	else{
		header("Location:signin.php");
		exit();
	}
?>

		</body>
		</html>