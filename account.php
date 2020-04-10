<?php
session_start();
if (isset($_SESSION['uname']) && isset($_SESSION['pass']) && isset($_SESSION['email']) && isset($_SESSION['mobno']) && isset($_SESSION['utype']) && $_SESSION['utype']=="user") {?>
<!DOCTYPE html>
<html>
<head>
	<title>Your Account</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="style/homestyle.css">
</head>
<?php
	
	require_once("includes/dbConn.php");
	require_once("includes/usernav.php");
	$username=htmlspecialchars($_SESSION['uname']);
	$email=htmlspecialchars($_SESSION['email']);
?>
<body>
	<h1>Account Info</h1>
	<table>
		<tr>
			<td>Name:</td>
			<td><?php echo $username; ?></td>
		</tr>
	</table>

	<h1>Your Orders</h1>
	<?php
		$sql="SELECT * FROM orders WHERE Customer_ID='$email' ORDER BY ID DESC";
		$result=mysqli_query($conn,$sql);
		$resultCheck=mysqli_num_rows($result);
		if ($resultCheck>0) {
			while ($row=mysqli_fetch_assoc($result)) {?>
				<a href="orderdetails.php?id=<?php echo $row['ID'] ?>" style="text-decoration: none;color: black;"><div style="border:1px solid red;margin: 40px;padding: 20px;">
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
		
		require_once 'includes/footer.php';
	?>
</body>
</html>

<?php

}
else{
	header("Location:signin.php");
	exit();
}

?>