<?php
	if (isset($_POST['submit'])) {
		$selector=$_POST['selector'];
		$validator=$_POST['validator'];
		$password=$_POST['pwd'];
		$pass_repeat=$_POST['pwd-repeat'];
		if (empty($password) || empty($pass_repeat)) {
			header("Location:../create-new-password.php?selector=$selector&validator=$validator&error=empty");
			exit();
		}
		if($password !==  $pass_repeat){
			header("Location:../create-new-password.php?selector=$selector&validator=$validator&error=invalid");
			exit();
		}
		if (!strlen($pass_repeat)>=6) {
			header("Location:../create-new-password.php?selector=$selector&validator=$validator&error=invalid");
		}
		
		function post_captcha($user_response) {
	        $fields_string = '';
	        $fields = array(
	            'secret' => '6LeTO40UAAAAAIqiFnxq22jsYoNw4Gt5dPgL9a5_',
	            'response' => $user_response
	        );
	        foreach($fields as $key=>$value)
	        $fields_string .= $key . '=' . $value . '&';
	        $fields_string = rtrim($fields_string, '&');

	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
	        curl_setopt($ch, CURLOPT_POST, count($fields));
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

	        $result = curl_exec($ch);
	        curl_close($ch);

	        return json_decode($result, true);
	    }

	    // Call the function post_captcha
	    $res = post_captcha($_POST['g-recaptcha-response']);

	    if (!$res['success']) {
	    	header("Location:../create-new-password.php?selector=$selector&validator=$validator&error=captcha");
			exit();
		} 
		else{

			$currentTime=date("U");
			require_once 'dbConn.php';

			$sql="SELECT * FROM resetpassword WHERE Selector='$selector' AND ExpiryTime >= '$currentTime'";
			$result=mysqli_query($conn, $sql);
			if (!$row=mysqli_fetch_assoc($result)) {
				header("Location:../create-new-password.php?selector=$selector&validator=$validator&error=expired");
				exit();
			}
			else{
				$validatorBin=hex2bin($validator);
				$validatorCheck=password_verify($validatorBin, $row['Validator']);

				if ($validatorCheck === false) {
					echo "You need to re submit";
					exit();
				}
				else if ($validatorCheck === true) {
					$tokenEmail=$row['Email'];
					$hashedPassword = password_hash($pass_repeat, PASSWORD_DEFAULT);
					$sql= "UPDATE `userinfo` SET `Password` = '$hashedPassword' WHERE `userinfo`.`Email` = '$tokenEmail';";
					if(mysqli_query($conn, $sql)){
						header("Location:../signin.php?reset=success");
						exit();
					}
					else{
						header("Location:../signin.php?reset=failure");
						exit();
					}
				}
			}
		}	
	}
	else{
		header("Location:../forgot-password.php");
		exit();
	}
?>