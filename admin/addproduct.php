<?php
	session_start();
	if (isset($_SESSION['uname']) && isset($_SESSION['pass']) && isset($_SESSION['email'])&& isset($_SESSION['mobno']) && $_SESSION['utype']==="admin") { ?>

		
		<!DOCTYPE html>
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="includes/style.css">
			<link rel="stylesheet" type="text/css" href="includes/admin_style.css">
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
			<title>Add Product</title>
		</head>
		<body>
		<?php  require_once 'includes/adminnav.php'; ?>

		<center><h1>Add New Product</h1></center>
			<?php 
				if (isset($_GET['error'])) {
					$error=$_GET['error'];
					if ($error=="empty") {?>
						<center><p style="text-decoration-color: red;"> Please fill in all the fields</p></center>
						<?php
					}
					else if ($error=="invalid") {?>
						<center><p style="text-decoration-color: red;"> Please fill in all the fields properly</p></center>
						<?php
					}
					else if ($error=="file") {?>
						<center><p style="text-decoration-color: red;"> Please choose a proper file.</p></center>
						<?php
					}
				}
				if (isset($_GET['addproduct'])) {
					$message=$_GET['addproduct'];
					if ($message=="success") {?>
						<center><p style="text-decoration-color: red;"> Successfully added the product</p></center>
						<?php
					}
					elseif ($message=="failure") {?>
						<center><p style="text-decoration-color: red;"> Sorry! something went wrong. Please try again.</p></center>
						<?php
					}
				}
			?>

			<form method="POST" action="includes/addProducts_process.php" class="form" style="margin-top: 4%;" id="ProductDetails" enctype="multipart/form-data">
				<label class="l">Product Name:</label><br>
					<input type="text" name="p_name" value="<?php if(isset($_GET['pname']) ){echo($_GET['pname']);}  ?>" placeholder="Enter the name of the product..." autocomplete="off"><br>
				<label class="l">Product Category:</label><br>
					<select name="productCategory" form="ProductDetails">
				  		<option value="menclothes">Men's Wear</option>
				  		<option value="womanclothes">Women's Wear</option>
				  		<option value="kidsclothes">Kid's Wear</option>
				  		<option value="menshoes">Men's Shoes</option>
				  		<option value="womanshoes">Woman's Shoes</option>
				  		<option value="kidshoes">Kids's Shoes</option>
				  		<option value="accessories">Accessories</option>
					</select><br>
			  	<label class="l">Product Quantity</label><br>
			  		<input type="number" name="xsqty" autocomplete="off" placeholder="XS size quantity" min="1">
			  		<input type="number" name="sqty" autocomplete="off" placeholder="S size quantity" min="1">
			  		<input type="number" name="mqty" autocomplete="off" placeholder="M size quantity" min="1">
			  		<input type="number" name="lqty" autocomplete="off" placeholder="L size quantity" min="1">
			  		<input type="number" name="xlqty" autocomplete="off" placeholder="XL size quantity" min="1"><br>
				<label class="l">Product Brand:</label><br>
					<input type="text" name="p_brand" value="<?php if(isset($_GET['pbrand']) ){echo($_GET['pbrand']);}  ?>" placeholder="Enter the product brand here..." autocomplete="off"><br>
				<label class="l">Product Price:</label><br>
					<input type="text" name="p_price" value="<?php if(isset($_GET['pprice']) ){echo($_GET['pprice']);}  ?>" placeholder="Enter the product price..." autocomplete="off"><br>
				<label class="l">Product Description:</label><br>
					<textarea rows="4" cols="70" name="p_desc" form="ProductDetails" placeholder="Give a description of the product over here....." style="margin-bottom: 20px;" value="<?php if(isset($_GET['pdesc']) ){echo($_GET['pdesc']);}  ?>"></textarea><br>
				<label class="l">Product Image:</label><br>
					<input type="file" name="img" multiple><br>
					<input type="submit" name="submit" style="margin-top: 20px;">
			</form>

		</body>
		</html>

		<?php
	}
	else{
		header('Location:../signin.php');
		exit();
	}

?>	