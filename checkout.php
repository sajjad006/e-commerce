<?php
session_start();
if (isset($_SESSION['uname']) && isset($_SESSION['pass']) && isset($_SESSION['email']) && isset($_SESSION['mobno']) && isset($_SESSION['utype']) && $_SESSION['utype']=="user") {
	if (!strpos($_SERVER['HTTP_REFERER'], "cart.php")) {
		header("Location:cart.php");
		exit();
	}
	require_once("includes/dbConn.php");
	$username=htmlspecialchars($_SESSION['email']);
?>	
	<!DOCTYPE html>
	<html>
	<head>
		<title>CHECKOUT</title>
		<link rel="stylesheet" type="text/css" href="style/homestyle.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
	</head>
	<body>
		<?php require_once("includes/usernav.php"); ?>
		<center><h1>SHIPPING AND BILLING DETAILS</h1></center>
		<form method="POST" action="includes/checkout_process.php">
			<h2>SHIPPING ADDRESS</h2>		
			<fieldset id="add" style="display: none;">
				Full Name:
				<input type="text" name="name" placeholder="Name..."><br>
				Mobile Number:
				<input type="text" name="mobno" placeholder="Mobile Number"><br>
				House/Flat Number:
				<input type="text" name="house" placeholder="House Number"><br>
				Street Name:
				<input type="text" name="street" placeholder="Street Name"><br>
				City:
				<input type="text" name="city" placeholder="City"><br>
				State:
				<input type="text" name="state" placeholder="State"><br>
				Pincode:
				<input type="text" name="pincode" placeholder="Pincode"><br>
				
			</fieldset>

			<?php
			$sql="SELECT * FROM address WHERE Username='$username'";
			$result=mysqli_query($conn, $sql);
			$resultCheck=mysqli_num_rows($result);
			if ($resultCheck<1) {?>
				<script type="text/javascript">
					var x = document.getElementById("add");
					x.style.display = "block";
				</script>
				<?php
			}
			else{		
				echo "<h3 class='hello'>Addresses:</h3>";
				while ($row=mysqli_fetch_assoc($result)) {
					?><div class="hello" style="display: inline-block;margin: 20px;"><input type="radio" name="address" style="width: 20px;height: 2em;" checked value="<?php echo $row['ID']; ?>"><?php
					echo $row['Name']."<br>".$row['Mobno']."<br>".$row['House']." , ".$row['Street']."<br>".$row['City']." , ".$row['State']." ".$row['Pincode']."<br>";?></div>
		<?php	}			
				?>
				<button type="button" id="addbtn" onclick="display()" style="display: block;" class="a">Add New Addres</button>
				<button type="button" id="previous" onclick="disp()" style="display: none;" class="a">Use Previous Address</button>
				<script type="text/javascript">
					function display() {
						document.getElementById('add').style.display="block";
						document.getElementById("addbtn").style.display="none";
						document.getElementById("previous").style.display="block";
						var elements = document.getElementsByClassName("hello");

    					for (var i = 0; i < elements.length; i++){
        					elements[i].style.display = "none";
    					}
					}	
					function disp() {
						document.getElementById("add").style.display="none";
						document.getElementById("addbtn").style.display="block";
						document.getElementById("previous").style.display="none";
						var elements=document.getElementsByClassName("hello");

						for (var i = 0; i < elements.length; i++) {
							elements[i].style.display="block";
						}
					}
				</script>
	<?php			
		}	
	?>

			<br><br><br><br>
			PAYMENT OPTION
			<fieldset>
				<input type="radio" name="pay" value="cod" checked>Cash On Delivery<br>
				<input type="radio" name="pay" value="card">Debit/Credit Card<br>
				<input type="radio" name="pay" value="net">Net Banking<br>
				<input type="radio" name="pay" value="wallet">E-Wallet<br>  
			</fieldset>

			<br><br><br><br>
			<center><h1>Product Details:</h1></center><br><br>
			<?php
				$uid=htmlspecialchars($_SESSION['email']);
				$sql="SELECT * FROM cart WHERE U_ID='$uid' AND Purchased='0'";
				$result=mysqli_query($conn, $sql);	
				$resultCheck=mysqli_num_rows($result);
				$total=0;
				$itemsno=0;
				while ($prow = mysqli_fetch_assoc($result)) {
					$pid=$prow['P_ID'];
					$qty=$prow['Quantity'];
					$size=$prow['Size'];
					$sql="SELECT * FROM productdetails WHERE P_ID='$pid'";
					$result2=mysqli_query($conn, $sql);
					while ($row = mysqli_fetch_assoc($result2)) {
						$dst="admin/includes/".$row['P_Image'];
						$name=strtoupper($row['P_Name']);
						$brand=strtoupper($row['P_Brand']);	
						$price=$row['P_Price'];
						$total=$total+($price*$qty);
						$itemsno+=1;
						$query="SELECT * FROM productdetails WHERE P_ID='$pid'";
						$check=mysqli_query($conn,$query);
						while ($row2=mysqli_fetch_assoc($check)) {
							if ($size=="XS") {
								$qty2=$row2['XSQTY'];
							}
							else if ($size=="S") {
								$qty2=$row2['SQTY'];
							}
							else if ($size=="M") {
								$qty2=$row2['MQTY'];
							}
							else if ($size=="L") {
								$qty2=$row2['LQTY'];
							}
							else if ($size=="XL") {
								$qty2=$row2['XLQTY'];
							}	

							if ($qty2<$qty) {
							?>
								<script type="text/javascript">
									window.location.replace("addtocart.php?q=d&id=<?php echo $pid; ?>&size=<?php echo $size; ?>&err=qty");
								</script>
				<?php		}
						}
						
						?>
				  		<div style="margin-left:40px;">
				  			<img src="<?php echo $dst; ?>" width="200px;" height="200px;" style="display: inline-block;margin-top: 20px;">
				  			<div style="display: inline-block;margin-left: 80px;">
				  				<h1><?php echo $brand."   ".$name; ?></h1>
				  				<h2>Rs. <?php echo $price; ?></h2>
				  				<h4 style="display: inline-block;">Quantity:</h4>
				  				<input type="number" name="qty" value="<?php echo $qty; ?>" style="width: 90px;display: inline-block;" readonly>
				  				<h4 style="display: inline-block;">Size:</h4>
				  				<input type="text" name="size" value="<?php echo $size; ?>" style="width: 60px;display: inline-block;font-size: 0.7em;border-radius: 0;" readonly><br><br>
				  				<a href="addtocart.php?q=d&id=<?php echo $pid; ?>&size=<?php echo $size; ?>" class="a">Delete</a>
				  				<a href="" class="a">Save For Later</a>
				  			</div><hr style="margin-left: 0;">
				  		</div>
		  				<?php
					}
				}
				
				if ($total==0) {
					header("Location:home.php");
					exit();
				}
			?>
			<center><h2 style="display: inline-block;">TOTAL AMOUNT:  </h2><h2 style="display: inline-block;" id="total">Rs. <?php echo $total; ?></h2></center>
			<input type="hidden" name="total" value="<?php echo $total; ?>">
			<center><input type="submit" name="submit" value="PLACE YOUR ORDER" style="display: block;cursor: pointer;"></center>
			<center><p>You will be redirected to the payment gateway(if needed) after placing your order.</p></center>

		</form>

	</body>
	</html>
	<?php
}
else{
	header("Location:home.php");
	exit();
}

?>