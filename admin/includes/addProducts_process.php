<?php
include('dbConn.php');
if (isset($_POST['submit'])) {
	$pname=htmlspecialchars($_POST['p_name']);
	$pcat=htmlspecialchars($_POST['productCategory']);
	$pbrand=htmlspecialchars($_POST['p_brand']);
	$pdesc=htmlspecialchars($_POST['p_desc']);
	$pprice=htmlspecialchars($_POST['p_price']);
	$xsqty=htmlspecialchars($_POST['xsqty']);
	$sqty=htmlspecialchars($_POST['sqty']);
	$mqty=htmlspecialchars($_POST['mqty']);
	$lqty=htmlspecialchars($_POST['lqty']);
	$xlqty=htmlspecialchars($_POST['xlqty']);

	if (empty($pname) || empty($pcat) || empty($pbrand) || empty($pdesc) || empty($pprice)) {
		header("Location:../addProduct.php?error=empty&pname=$pname&pcat=$pcat&pbrand=$pbrand&pdesc=&pdesc&pprice=$pprice");
		exit();
	}
	else{
		if (!preg_match("/^[A-Za-z0-9 -]*$/", $pname) || !preg_match("/^[A-Za-z0-9 ,-]*$/", $pbrand) || !preg_match("/^[A-Za-z0-9., -]*$/", $pdesc) || !preg_match("/^[0-9]*$/", $pprice)) {
			header("Location:../addProduct.php?error=invalid&pname=$pname&pcat=$pcat&pbrand=$pbrand&pdesc=&pdesc&pprice=$pprice");
			exit();
		}
		else{
			if (strlen($pname)>60) {
				header("Location:../addProduct.php?error=name&pcat=$pcat&pbrand=$pbrand&pdesc=&pdesc&pprice=$pprice");
				exit();
			}
			else{
				$sql="SELECT * FROM productdetails WHERE P_Name='$pname' && P_Brand='$pbrand' && P_Category='$pcat'";
				$result=mysqli_query($conn, $sql);
				$resultCheck=mysqli_num_rows($result);
				if ($resultCheck>0) {
					header("Location:../addProduct.php?error=productexists&pcat=$pcat&pbrand=$pbrand&pdesc=&pdesc&pprice=$pprice");
					exit();
				}
				else{
					$image = $_FILES['img']['tmp_name'];
					$name=$_FILES['img']['name'];
					$allowed = array('jpeg','jpg','png');
					$fExt=explode('.', $name);
					$fActExt=strtolower(end($fExt));
					if (in_array($fActExt, $allowed)) {
						$fileNewName=uniqid('',true).".".$fActExt;
						$fileDestination='upload/'.$fileNewName;
						move_uploaded_file($image, $fileDestination);
						
						if($stmt=$conn->prepare("INSERT INTO productdetails (P_Name, P_Category, P_Brand, P_Description, P_Image, P_Price, P_ImgExt, XSQTY, SQTY, MQTY, LQTY, XLQTY) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)")){
							$stmt->bind_param("sssssisiiiii",$pname, $pcat, $pbrand,$pdesc,$fileDestination,$pprice,$fActExt,$xsqty,$sqty,$mqty,$lqty,$xlqty);
							$stmt->execute();
							$stmt->close();
							header("Location:../addProduct.php?addproduct=success");
							exit();
						}	
						else{
							header("Location:../addProduct.php?addproduct=failure");
							exit();
						}
					}
					else{
						header("Location:../addProduct.php?error=file&pname=$pname&pcat=$pcat&pbrand=$pbrand&pdesc=&pdesc&pprice=$pprice");
						exit();
					}
				}
			}
		}
	}
}
else{
	header("Location:../addProduct.php");
	exit();
}
	
?>
