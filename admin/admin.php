<?php
session_start();
if (isset($_SESSION['uname']) && isset($_SESSION['pass']) && isset($_SESSION['email'])&& isset($_SESSION['mobno']) && isset($_SESSION['utype']) && $_SESSION['utype']==="admin") {
	 ?>
	<!DOCTYPE html>
	<html>
	<head>
		<title>ADMIN</title>
		<link rel="stylesheet" type="text/css" href="includes/style.css">
		<link rel="stylesheet" type="text/css" href="includes/admin_style.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>
	<body>

	<!DOCTYPE html>
	<html>
	<head>
		<title>ADMIN</title>
	</head>
	<body>
<?php
	require_once 'includes/adminnav.php';
	require_once '../includes/dbConn.php';
	?>
  	<center><h1>ADMIN PANEL</h1></center>
	<?php
		$sql="SELECT * FROM orders";
		$result=mysqli_query($conn,$sql);
		$ordernums=mysqli_num_rows($result);
		echo "<h2>Number of orders : $ordernums</h2>";
		$sql="SELECT SUM(Total) AS total FROM orders";
		$result=mysqli_query($conn,$sql);
		while ($row=mysqli_fetch_assoc($result)) {
			$total=$row['total'];
			echo "<h2>Total earnings : $total</h2>";
		}
		$sql="SELECT * FROM userinfo WHERE Usertype='user'";
		$usernum=mysqli_num_rows(mysqli_query($conn, $sql));
		echo "<h2>Number of customers : $usernum</h2>";
}
else{
	header("Location:../signin.php");
	exit();
}

?>


</body>
</html>