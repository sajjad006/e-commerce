<?php
	session_start();
	if (isset($_POST['return']) || isset($_POST['cancel'])) {
		require_once 'dbConn.php';
		$payment=$_POST['payment'];
		$orderid=$_POST['orderid'];
		$total=$_POST['total'];
		$name=$_SESSION['uname'];
		$email=$_SESSION['email'];
		function cancelmail(){
			$header = "From: order-update@techsajjad.tk\r\n";
			$header.= "Reply-To: info@techsajjad,tk\r\n";
			$header.= "MIME-Version: 1.0\r\n";
			$header.= "Content-Type: text/html; charset=UTF-8\r\n";
			$subject="Cancellation Request for your order #".$orderid;
			$body = "<p>Dear ".$name.",</p>";
			$body.= "<p>We have received your request to cancel your order #".$orderid." and will process your refund if applicable within 2-3 days. Hope to serve you better in the future.</p>";
			mail($email, $subject, $body, $header);
			header("Location: ../orderdetails.php?id=$orderid&cancel=failure");
			exit();
		}

		if (isset($_POST['return'])) {
			$reason=htmlspecialchars($_POST['reason']);
			$sql="SELECT * FROM returns WHERE Order_ID='$orderid'";
			if (!$result=mysqli_query($conn, $sql)) {
				header("Location: ../orderdetails.php?id=$orderid&return=failure");
				exit();
			}
			else{
				$resultCheck=mysqli_num_rows($result);
				if ($resultCheck>0) {
					header("Location: ../orderdetails.php?id=$orderid&return=failure");
					exit();
				}
				else{
					$sql="UPDATE orders SET Order_Status='returned' WHERE Order_ID='$orderid'";
					mysqli_query($conn, $sql);
					$sql="INSERT INTO returns (Order_ID, Status, Refund_Mode, Refund_Amount, Reason) VALUES ('$orderid', 'initiated', '$payment', '$total','$reason')";
					if (mysqli_query($conn, $sql)) {
						$email=$_SESSION['email'];
						$header = "From: order-update@techsajjad.tk\r\n";
						$header.= "Reply-To: info@techsajjad,tk\r\n";
						$header.= "MIME-Version: 1.0\r\n";
						$header.= "Content-Type: text/html; charset=UTF-8\r\n";
						$subject="Return Request for your order #".$orderid;
						$body = "<p>Dear ".$name.",</p>";
						$body.= "<p>We have received your request to return your order #".$orderid." and will process your refund once the product(s) are picked up. Pick up schedule will be sent to you via SMS. Hope to serve you better in the future.</p>";
						mail($email, $subject, $body, $header);
						header("Location: ../orderdetails.php?id=$orderid&return=success");
						exit();
					}
					else{
						header("Location: ../orderdetails.php?id=$orderid&return=failure");
						exit();
					}
				}
			}
		}
		if (isset($_POST['cancel'])) {
			if ($payment=="cod") {
				$sql="UPDATE orders SET Order_Status='cancelled' WHERE ID='$orderid'";
				$header = "From: order-update@techsajjad.tk\r\n";
				$header.= "Reply-To: info@techsajjad,tk\r\n";
				$header.= "MIME-Version: 1.0\r\n";
				$header.= "Content-Type: text/html; charset=UTF-8\r\n";
				$subject="Cancellation Request for your order #".$orderid;
				$body = "<p>Dear ".$name.",</p>";
				$body.= "<p>We have received your request to cancel your order #".$orderid." and will process your refund if applicable within 2-3 days. Hope to serve you better in the future.</p>";
				mail($email, $subject, $body, $header);
				header("Location: ../orderdetails.php?id=$orderid&cancel=success");
				exit();
			}
			else if ($payment=="card" || $payment=="net") {
				$sql="SELECT * FROM payments WHERE Order_ID='$orderid'";
				$result=mysqli_query($conn, $sql);
				$resultCheck=mysqli_num_rows($result);
				if ($resultCheck > 0) {
					$row=mysqli_fetch_assoc($result);
					$payment_id=$row['payment_id'];
					$ch = curl_init();

					curl_setopt($ch, CURLOPT_URL, 'https://test.instamojo.com/api/1.1/refunds/');
					curl_setopt($ch, CURLOPT_HEADER, FALSE);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER,
					            array("X-Api-Key:test_81c8739502522266621c7b43888",
					                  "X-Auth-Token:test_7fbd147f050b082a5c7968febeb"));
					$payload = Array(
					    'payment_id' => $payment_id,
					    'type' => 'QFL',
					    'body' => "Customer isn't satisfied with the quality"
					);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
					$response = curl_exec($ch);
					curl_close($ch); 

					$json_decode=json_decode($response, true);
					$status = $json_decode['refund']['status'];
					$id = $json_decode['refund']['id'];
					if ($status=="Refunded") {
						$sql="UPDATE orders Set Order_Status='cancelled' WHERE ID='$orderid'";
						mysqli_query($conn, $sql);
						$header = "From: order-update@techsajjad.tk\r\n";
						$header.= "Reply-To: info@techsajjad,tk\r\n";
						$header.= "MIME-Version: 1.0\r\n";
						$header.= "Content-Type: text/html; charset=UTF-8\r\n";
						$subject="Cancellation Request for your order #".$orderid;
						$body = "<p>Dear ".$name.",</p>";
						$body.= "<p>We have received your request to cancel your order #".$orderid." and will process your refund if applicable within 2-3 days. Hope to serve you better in the future.</p>";
						mail($email, $subject, $body, $header);
						header("Location: ../orderdetails.php?id=$orderid&cancel=success");
						exit();
					}
					else{
						header("Location:../index.php");
						exit();
					}
					
				}
				else{
					header("Location: ../orderdetails.php?id=$orderid&return=failure");
					exit();
				}
			}
		}	
		else{
			header("Location:../account.php");
			exit();
		}	
	}
	else{
		header("Location: ../account.php");
		exit();
	}
?>