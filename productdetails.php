<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>WELCOME</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="style/homestyle.css">
</head>
<body>
	<?php require_once("includes/usernav.php");
	require_once("includes/dbConn.php");
	if (isset($_GET['id'])) {
		$id=htmlspecialchars($_GET['id']);
		$sql="SELECT * FROM productdetails WHERE P_ID='$id'";
		$result=mysqli_query($conn, $sql);
		$resultCheck=mysqli_num_rows($result);
		if ($resultCheck<=0) {
			echo "<center><h1>Sorry couldnot find product</h1></center>";
			exit();
		}
		else{
			while ($row=mysqli_fetch_assoc($result)) {
				$name=strtoupper($row['P_Name']);
				$cat=strtoupper($row['P_Category']);
				$desc=strtoupper($row['P_Description']);
				$price=$row['P_Price'];
				$brand=strtoupper($row['P_Brand']);
				$XSQTY=$row['XSQTY'];
				$SQTY=$row['SQTY'];
				$MQTY=$row['MQTY'];
				$LQTY=$row['LQTY'];
				$XLQTY=$row['XLQTY'];
				$dst="admin/includes/".$row['P_Image'];
			}
		}
	}
	else{
		header("Location:home.php");
		exit();
	}
?>
 	<img src="<?php echo $dst; ?>" width="30%" height="600px" style="margin: 20px;margin-left: 130px;float: left;">	
  	<div style="float: right;width: 40%;">
  		
  		<h2 style="display: inline-block;"><?php echo $cat; ?></h2><br> 
  		<h2 style="display: inline-block;"><?php echo $brand."   ".$name; ?></h2>
  		<p style="font-size: 1.4em;">Rs. <?php echo $price; ?></p><hr>
  		<h1 style="display: inline-block;">Size:</h1>
  		<select form="add" name="size" style="width: 400px;display: inline-block;">
  			<option value="XS">XS</option>
  			<option value="S">S</option>
  			<option value="M">M</option>
  			<option value="L">L</option>
  			<option value="XL">XL</option>
  		</select><hr>
  		<!--<a href="addtocart.php?q=a&id=<?php echo $id; ?>&uri=det" class="a">ADD TO CART<i class="fas fa-shopping-cart fa-2x"></i></a>-->
  		<form method="POST" action="addtocart.php?q=a&id=<?php echo $id; ?>&uri=det" id="add">
  			<h3 style="display: inline-block;">Quantity:</h3>
  			<input type="number" name="qty" placeholder="Quantity..." min="1" style="display: inline-block;width: 200px;border-radius: 0;" value="1" autocomplete="off"><br>
  			<button type="submit" class="a" style="display: inline-block;width: 200px;cursor: pointer;"><i class="fas fa-shopping-cart fa-2x"></i>ADD TO CART</button>
			<button type="button" class="a" style="display: inline-block;width: 200px;cursor: pointer;"><i class="far fa-heart fa-2x"></i>SAVE FOR LATER</button>
  		</form><hr>
  		<h3>Product Description:</h3>
  		<p style="font-size: 1.4em;overflow: auto;height: 140px;"><?php echo $desc; ?></p>
  	</div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><hr>

<div style="width: 100%;">
	<div style="display: inline-block;float: left;border:1px solid black;width: 40%;margin: 20px;padding: 20px;height: 500px;background-color: #f44336;overflow: auto;" class="rev">
		<h1>Write a Review:</h1>
		<form id="rform" action="" method="POST">
			<label><h2>Give a heading:</h2></label><br>
			<textarea rows="2" cols="70" name="heading" form="rform" placeholder="Whats most important about the product?" style="border: 1px solid black;border-radius: 5px;resize: none;"></textarea><br>
			<label><h2>Write your review:</h2></label><br>
			<textarea rows="6" cols="70" name="review" form="rform" placeholder="What did you like or dislike about the product? how did you use it...." style="border: 1px solid black;border-radius: 5px;resize: vertical;font-size: 1em;"></textarea><br><br>
			<input type="submit" form="rform" name="submitreview" value="SUBMIT" style="border-radius: 5px;">
		</form>
	</div>

	<div style="display: inline-block;float: right;border: 1px solid black;width: 45%;margin: 20px;padding: 20px;height: 500px;overflow: auto;background-color: #f44336;" class="rev">
		<h1>Reviews</h1>
		<?php  
			$sql="SELECT * FROM reviews WHERE P_ID='$id'";
			$result=mysqli_query($conn,$sql);
			$resultCheck=mysqli_num_rows($result);
			if ($resultCheck<=0) {
				echo "<h1>Mmm... no one reviewed this prodoct...";
			}
			while ($row=mysqli_fetch_assoc($result)) {
				$review=$row['Review'];
				$user=$row['U_ID'];
				$heading=$row['Heading'];?>
				<div style="border: 1px solid black;background-color: white;" class="row">
					<h2 style="margin: 10px;"><?php echo $heading; ?></h2>
					<h3 style="margin: 10px;"><?php echo $user; ?></h3>
					<p style="margin: 10px;"><?php echo $review;; ?></p>
				</div>
<?php		}
		?>

	</div>
</div>


<?php
	
	if (isset($_POST['submitreview'])) {
		if (isset($_SESSION['uname'])) {
			$name=$_SESSION['email'];
			$heading=htmlspecialchars($_POST['heading']);
			$review=htmlspecialchars($_POST['review']);
			$sql="INSERT INTO reviews(P_ID,U_ID,Heading,Review) VALUES ('$id','$name','$heading','$review')";
			if(mysqli_query($conn,$sql)){?>
				<script type="text/javascript">
					window.location.replace("productdetails.php?id=<?php echo $id ?>");
					alert("Successfully reviewed the product !");
				</script>
<?php		}
		}
		else{
			header("Location:signin.php");
			exit();
		}
	}

	$add="";
	if (isset($_GET['add'])){ 
		$add=htmlspecialchars($_GET['add']);
	}

	switch ($add) {
		case 'success':
			?>
			<script type="text/javascript">confirm('Successfully added to cart');</script>
			<?php
			break;
		case 'failure':
			?>
			<script type="text/javascript">confirm('Sorry failed to add to cart');</script>
			<?php
			break;
		case 'signin':
			?>
			<script type="text/javascript">confirm('Signin to add items to cart');</script>
			<?php
			break;

		default:
			
			break;
	}

	$err="";
	if (isset($_GET['error'])) {
		$err=htmlspecialchars($_GET['error']);
	}
	if ($err=="qty") {
		?>
		<script type="text/javascript">confirm('Unavailable Quantity');</script>
		<?php
	}
?>  	
<?php require_once 'includes/footer.php'; ?>
</body>
</html>