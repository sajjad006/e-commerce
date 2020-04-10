<?php
	session_start();
	require_once 'includes/dbConn.php';
	if (isset($_SESSION['uname']) && isset($_SESSION['email']) && isset($_SESSION['pass'])) {
		$email=$_SESSION['email'];
		if ($_GET['payment_status']=="Credit" && isset($_SESSION['email']) && isset($_SESSION['uname']) && isset($_SESSION['pass'])) {

			$pid=$_GET['payment_id'];
			$pri=$_GET['payment_request_id'];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://test.instamojo.com/api/1.1/payment-requests/'.$pri.'/'.$pid.'/');
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($ch, CURLOPT_HTTPHEADER,
			            array("X-Api-Key:test_81c8739502522266621c7b43888",
			                  "X-Auth-Token:test_7fbd147f050b082a5c7968febeb"));
			$response = curl_exec($ch);
			curl_close($ch); 
			$json_decode = json_decode($response,true);
			$status=$json_decode['payment_request']['payment']['status'];
			$amount=$json_decode['payment_request']['payment']['amount'];
			if ($status=="Credit") {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'http://local.instamojo.com:5000/api/1.1/payment-requests/'.$pri.'/disable/');
				curl_setopt($ch, CURLOPT_HEADER, FALSE);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				curl_setopt($ch, CURLOPT_HTTPHEADER,
				            array("X-Api-Key:test_81c8739502522266621c7b43888",
				                  "X-Auth-Token:test_7fbd147f050b082a5c7968febeb"));
				$response = curl_exec($ch);
				curl_close($ch);
				
				$sql="SELECT * FROM orders WHERE Customer_ID='$email' ORDER BY ID DESC LIMIT 1";
				$result=mysqli_query($conn,$sql);
				$row=mysqli_fetch_assoc($result);
				$id=$row['ID'];

				$header = "MIME-Version: 1.0" . "\r\n";
				$header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				$header='From: order-update@techsajjad.tk' . "\r\n" .'Reply-To: info@techsajjad.tk';
				$subject="Your order #".$id." is confirmed";
				$name1=$_SESSION['uname'];
				$message="Hello $name1 ,"."\n"."
			  	Your order has been successfully placed and we will send you a confirmation email once it is shipped.";
				$email=$_SESSION['email'];
				mail("$email", $subject, $message,$header);

				
				$sql="UPDATE orders SET Order_Status='confirmed' WHERE ID='$id'";
				mysqli_query($conn,$sql);

				$email=$_SESSION['email'];
				$sql="SELECT * FROM orders WHERE Customer_ID='$email' ORDER BY ID DESC LIMIT 1";
				$result=mysqli_query($conn,$sql);
				$row=mysqli_fetch_assoc($result);
				$id=$row['ID'];
				$pid=htmlspecialchars($_GET['payment_id']);
				$pri=htmlspecialchars($_GET['payment_request_id']);
				$sql="INSERT INTO payments(Order_ID, payment_request_id, payment_id, amount) VALUES('$id','$pri','$pid','$amount')";
				mysqli_query($conn,$sql);
                
				header("Location:account.php");
				exit();
		    }
		}
		else{
			$email=$_SESSION['email'];
			$sql="SELECT * FROM orders WHERE Customer_ID='$email' ORDER BY ID DESC LIMIT 1";
			$result=mysqli_query($conn,$sql);
			$row=mysqli_fetch_assoc($result);
			$id=$row['ID'];
			$sql="UPDATE orders SET Order_Status='failed' WHERE ID='$id'";
			mysqli_query($conn,$sql);

			$header = "MIME-Version: 1.0" . "\r\n";
			$header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$header='From: order-update@techsajjad.tk' . "\r\n" .'Reply-To: info@techsajjad.tk';
			$subject="Your order #".$orderid;
			$name1=$_SESSION['uname'];
			$message="Hello $name1 ,"."\n"."
		  	Your TechSajjad order# ($id) has been cancelled because you couldnot fulfill your payment. Sorry for the inconvinience caused. Please place your order again.";
			$email=$_SESSION['email'];
			mail("$email", $subject, $message,$header);

			header("Location:cart.php");
			exit();
		}
	}
	else{
		header("Location:home.php");
	}	
?>