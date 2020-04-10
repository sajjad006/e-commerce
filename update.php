<?php
	require_once 'includes/dbConn.php';

	$sql="UPDATE orders SET Order_Status='failed' WHERE Order_Status='placed'";
	mysqli_query($conn, $sql);

	$sql="SELECT * FROM orders WHERE Order_Status='placed'";
	$result=mysqli_query($conn, $sql);
	while ($row=mysqli_fetch_assoc($result)) {
		$email=$row['Customer_ID'];
		$header = "MIME-Version: 1.0" . "\r\n";
		$header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$header='From: order-update@techsajjad.tk' . "\r\n" .'Reply-To: info@techsajjad.tk';
		$subject="Your order #".$orderid;
		$name1=$_SESSION['uname'];
		$message="Hello $name1 ,"."\n"."
	  	Your order has been cancelled because you couldnot fulfil your payment. Sorry for the inconvinience caused. Please place your order again.";
		$email=$_SESSION['email'];
		mail("$email", $subject, $message,$header);
	}

?>