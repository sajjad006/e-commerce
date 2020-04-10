<?php
	session_start();
	if (isset($_SESSION['uname']) && isset($_SESSION['pass']) && isset($_SESSION['email']) && isset($_SESSION['mobno']) && isset($_SESSION['utype']) && $_SESSION['utype']==="user") {
		require_once("includes/dbConn.php");
		$uid=htmlspecialchars($_SESSION['email']);
		$sql="SELECT * FROM cart WHERE U_ID='$uid' AND Purchased='0'";
		$result=mysqli_query($conn, $sql);	
		$resultCheck=mysqli_num_rows($result);

	}
	else{
		header("Location:signin.php");
		exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>CART</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="style/homestyle.css">
</head>
<body>
	<?php
	if (isset($_GET['err'])) {
		$e=htmlspecialchars($_GET['err']);
		if ($e=="qty") {
			?>
			<script type="text/javascript">confirm("Some products have been removed from your cart due to unavailable quantity.");</script>
			<?php
		}
	}
	?>
	<?php require_once("includes/usernav.php"); ?>

  	<div>
		<h2 style="margin-left: 40px;display: inline-block;">Shopping Cart</h2>
		<button type="button" class="a" onclick="addtocart.php" style="display: inline-block;float: right;margin-top: 10px;margin-left: 60px;">EMPTY CART</button>
	</div><hr>	  

	<form method="GET" action="includes/checkout.php">	
<?php	
	if ($resultCheck<1) {
		echo "<center><h2>Sorry! You do not have any products in your cart.</h2></center>";
		exit();
	}
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
				?>
		  		<div style="margin-left:40px;">
		  			<img src="<?php echo $dst; ?>" width="200px;" height="200px;" style="display: inline-block;margin-top: 20px;">
		  			<div style="display: inline-block;margin-left: 80px;">
		  				<p><?php echo $brand."   ".$name; ?></hp>
		  				<p>Rs. <?php echo $price; ?></p>
		  				<p>Size: <?php echo $size; ?></p>
		  				<p style="display: inline-block;">Quantity:</p>
		  				<input type="number" name="qty" value="<?php echo $qty; ?>" style="width: 90px;display: inline-block;border:1px solid black;" readonly><br><br>
		  				<a style="cursor: pointer;" href="addtocart.php?q=d&id=<?php echo $pid; ?>&size=<?php echo $size; ?>" class="a">Delete</a>
		  				<a style="cursor: pointer;" href="" class="a">Save For Later</a>
		  			</div><hr style="margin-left: 0;">
		  		</div>
		  	<?php
		}
	}


?>
	<center>
		<div style="display: inline-block;background-color: #9b9ea3;margin: 40px;border-radius: 0.3cm;padding: 20px;">
			<h1>SUB TOTAL( <?php echo $itemsno; ?> item):</h1>	
			<h2>Rs. <?php echo $total; ?></h2>
			<!--<center><input type="submit" name="checkout" value="PROCEED TO CHECKOUT" style="background-color: #ffe311;"></center>-->
			<center><a href="checkout.php" style="background-color: #ffe311;text-decoration: none;color: black;padding: 10px;">PROCEED TO CHECKOUT</a></center>
		</div>
	</form>
	</center>
	<?php require_once 'includes/footer.php'; ?>
</body>
</html>
