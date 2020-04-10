<?php
session_start();
if (isset($_SESSION['uname']) && isset($_SESSION['pass']) && isset($_SESSION['email']) && isset($_SESSION['mobno']) && isset($_SESSION['utype']) && $_SESSION['utype']=="admin") {?>
<!DOCTYPE html>
<html>
<head>
	<title>View Orders</title>
	<link rel="stylesheet" type="text/css" href="includes/style.css">
	<link rel="stylesheet" type="text/css" href="includes/admin_style.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script type="text/javascript" src="includes/adminscript.js"></script></head>
<?php
	
	require_once("includes/dbConn.php");
	require_once 'includes/adminnav.php';
	$username=htmlspecialchars($_SESSION['uname']);
	$email=htmlspecialchars($_SESSION['email']);?>
<body>

	<div class="choice">
		<h1>SELECT THE TYPE OF ORDER YOU WANT TO VIEW</h1>
		<input type="radio" name="ordertype" value="pending" onclick="func()"><b>PENDING ORDERS</b><br>
		<input type="radio" name="ordertype" value="confirmed" onclick="func()"><b>CONFIRMED ORDERS</b><br>
		<input type="radio" name="ordertype" value="shipped" onclick="func()"><b>SHIPPED</b><br>
		<input type="radio" name="ordertype" value="completed" onclick="func()"><b>COMPLETED</b><br>
	</div>    

	<div id="pendingorders" style="display: none;">
		<h1>Pending Orders</h1>
		<?php
			$sql="SELECT * FROM orders WHERE Order_Status='placed' ORDER BY ID";
			$result=mysqli_query($conn,$sql);
			$resultCheck=mysqli_num_rows($result);
			if ($resultCheck>0) {
				while ($row=mysqli_fetch_assoc($result)) {?>
					<a href="orderdetails.php?id=<?php echo $row['ID'] ?>" style="text-decoration: none;color: black;"><div style="border:1px solid red;margin: 40px;padding: 20px;background-color: white;">
						<p style="display: inline-block;"><b>ORDER #</b><?php echo $row['ID']; ?></p>
						<p style="display: inline-block;margin-left: 60px;"><b>Status: </b><?php echo strtoupper($row['Order_Status']); ?></p>
						<p><b>ORDER PLACED: </b><?php echo $row['Order_Date']; ?></p>
						<p><b>TOTAL: </b><?php echo $row['Total']; ?></p>
						<p><b>PAYMENT: </b><?php echo $row['Payment']; ?></p>
						<a style="text-decoration: none;" class="a" href="orderdetails.php?id=<?php echo $row['ID'] ?>">ORDER DETAILS</a>
					</div></a>
					<?php  
				} 
			}
			else{
				echo "<h3>YOU HAVE NO ORDERS</h3>";
			}
		?></div>
	
	<div id="confirmed" style="display: none;">
		<h1>Confirmed Orders</h1>
		<?php
			$sql="SELECT * FROM orders WHERE Order_Status='confirmed' ORDER BY ID";
			$result=mysqli_query($conn,$sql);
			$resultCheck=mysqli_num_rows($result);
			if ($resultCheck>0) {
				while ($row=mysqli_fetch_assoc($result)) {?>
					<a href="orderdetails.php?id=<?php echo $row['ID'] ?>" style="text-decoration: none;color: black;"><div style="border:1px solid red;margin: 40px;padding: 20px;background-color: white;">
						<p style="display: inline-block;"><b>ORDER #</b><?php echo $row['ID']; ?></p>
						<p style="display: inline-block;margin-left: 60px;"><b>Status: </b><?php echo strtoupper($row['Order_Status']); ?></p>
						<p><b>ORDER PLACED: </b><?php echo $row['Order_Date']; ?></p>
						<p><b>TOTAL: </b><?php echo $row['Total']; ?></p>
						<p><b>PAYMENT: </b><?php echo $row['Payment']; ?></p>
						<a style="text-decoration: none;" class="a" href="orderdetails.php?id=<?php echo $row['ID'] ?>">ORDER DETAILS</a>
					</div></a>
					<?php  
				} 
			}
			else{
				echo "<h3>YOU HAVE NO ORDERS</h3>";
			}
		?></div>

	<div id="shipped" style="display: none;">
		<h1>Shipped Orders</h1>
		<?php
			$sql="SELECT * FROM orders WHERE Order_Status='shipped' ORDER BY ID";
			$result=mysqli_query($conn,$sql);
			$resultCheck=mysqli_num_rows($result);
			if ($resultCheck>0) {
				while ($row=mysqli_fetch_assoc($result)) {?>
					<a href="orderdetails.php?id=<?php echo $row['ID'] ?>" style="text-decoration: none;color: black;"><div style="border:1px solid red;margin: 40px;padding: 20px;background-color: white;">
						<p style="display: inline-block;"><b>ORDER #</b><?php echo $row['ID']; ?></p>
						<p style="display: inline-block;margin-left: 60px;"><b>Status: </b><?php echo strtoupper($row['Order_Status']); ?></p>
						<p><b>ORDER PLACED: </b><?php echo $row['Order_Date']; ?></p>
						<p><b>TOTAL: </b><?php echo $row['Total']; ?></p>
						<p><b>PAYMENT: </b><?php echo $row['Payment']; ?></p>
						<a style="text-decoration: none;" class="a" href="orderdetails.php?id=<?php echo $row['ID'] ?>">ORDER DETAILS</a>
					</div></a>
					<?php  
				} 
			}
			else{
				echo "<h3>YOU HAVE NO ORDERS</h3>";
			}
		?></div>

	<div id="completed" style="display: none;">
		<h1>Completed Orders</h1>
		<?php
			$sql="SELECT * FROM orders WHERE Order_Status='delivered' OR Order_Status='returned' OR Order_Status='cancelled' ORDER BY ID";
			$result=mysqli_query($conn,$sql);
			$resultCheck=mysqli_num_rows($result);
			if ($resultCheck>0) {
				while ($row=mysqli_fetch_assoc($result)) {?>
					<a href="orderdetails.php?id=<?php echo $row['ID'] ?>" style="text-decoration: none;color: black;"><div style="border:1px solid red;margin: 40px;padding: 20px;background-color: white;">
						<p style="display: inline-block;"><b>ORDER #</b><?php echo $row['ID']; ?></p>
						<p style="display: inline-block;margin-left: 60px;"><b>Status: </b><?php echo strtoupper($row['Order_Status']); ?></p>
						<p><b>ORDER PLACED: </b><?php echo $row['Order_Date']; ?></p>
						<p><b>TOTAL: </b><?php echo $row['Total']; ?></p>
						<p><b>PAYMENT: </b><?php echo $row['Payment']; ?></p>
						<a style="text-decoration: none;" class="a" href="orderdetails.php?id=<?php echo $row['ID'] ?>">ORDER DETAILS</a>
					</div></a>
					<?php  
				} 
			}
			else{
				echo "<h3>YOU HAVE NO ORDERS</h3>";
			}
		?></div>



</body>
</html>

<?php

}
else{
	header("Location:../signin.php");
	exit();
}

?>