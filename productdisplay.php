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
	<?php  
	require_once("includes/dbConn.php");
	$type="";
	
	if (isset($_GET['type'])) {
		$type=htmlspecialchars($_GET['type']);
	}
	else
	{
		$type="unset";
	}


	if ($type=="men") {
		$sql="SELECT * FROM productdetails WHERE P_Category='menclothes'";	
	}
	else if ($type=="women") {
		$sql="SELECT * FROM productdetails WHERE P_Category='womanclothes'";
	}
	else if ($type=="kids") {
		$sql="SELECT * FROM productdetails WHERE P_Category='kidsclothes'";
	}
	else if ($type=="accessories") {
		$sql="SELECT * FROM productdetails WHERE P_Category='accessories'";
	}
	else {
		$sql="SELECT * FROM productdetails";	
	}
	if (isset($_GET['search'])) {
		$search=htmlspecialchars($_GET['search']);
		if (!empty($search)) {
			$sql="SELECT * FROM productdetails WHERE P_Name LIKE '%$search%' OR P_Brand LIKE '%$search%' OR P_Category LIKE '%$search%'";
		}
		else{
			header("Location:home.php");
		}
	}
?>
		<?php require_once("includes/usernav.php"); ?>



<?php
	$result=mysqli_query($conn, $sql);
	$resultCheck=mysqli_num_rows($result);
	if ($resultCheck<=0) {
		echo "<center><h1>Sorry couldnot find product</h1></center>";
		exit();
	}
	else{
		while ($row=mysqli_fetch_assoc($result)) {
			$id=$row['P_ID'];
			$dest="admin/includes/".$row['P_Image']; 
			$url="addtocart.php?q=a&id=$id&uri=disp&type=$type";
			?>
			<a href="productdetails.php?id=<?php echo $id; ?>">
				<div style="width: 200px;height: 350px;margin: 40px;display: inline-block;padding: 25px;background-color: #f44336;">
					<img src="<?php echo $dest; ?>" width="200px" height="250px">
					<p class="name" ><?php echo strtoupper($row['P_Brand'])." ".strtoupper($row['P_Name']); ?></p>
					<b><p class="price">Rs. <?php echo $row['P_Price']; ?></p></b>
					<!--<a href="<?php echo $url; ?>" class="cart"><i class="fas fa-shopping-cart fa-2x" title="Add to wishlist"></i></a>-->
					<a href="" class="heart"><i class="far fa-heart fa-2x" title="Add to wishlist"></i></a>
				</div>
			</a>	
			<?php
		}
	}
	$add="";
	if (isset($_GET['add'])) {
		$add=htmlspecialchars($_GET['add']);
	}

	switch ($add) {
		case 'success':
			?>
			<script type="text/javascript">window.alert('Successfully added to cart');</script>
			<?php
			break;
		case 'failure':
			?>
			<script type="text/javascript">window.alert('Sorry failed to add to cart');</script>
			<?php
			break;
		case 'signin':
			?>
			<script type="text/javascript">window.alert('Signin to add items to cart');</script>
			<?php
			break;

		default:
			
			break;
	}

	

?>


	<?php require_once 'includes/footer.php'; ?>

</body>
</html>