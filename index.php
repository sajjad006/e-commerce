<?php
	$login=false;
	session_start();
  require_once 'includes/dbConn.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Welcome</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="style/homestyle.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php require_once("includes/usernav.php"); ?>
	
<div class="w3-content w3-section" style="width: 100%">
  <img class="mySlides" src="images/img1.jpg" width="100%" height="200px">
  <img class="mySlides" src="images/img2.jpg" width="100%" height="200px">
  <img class="mySlides" src="images/img3.jpg" width="100%" height="200px">
</div>

<script>
var myIndex = 0;
carousel();

function carousel() {
    var i;
    var x = document.getElementsByClassName("mySlides");
    for (i = 0; i < x.length; i++) {
       x[i].style.display = "none";  
    }
    myIndex++;
    if (myIndex > x.length) {myIndex = 1}    
    x[myIndex-1].style.display = "block";  
    setTimeout(carousel, 4000); // Change image every 4 seconds
}
</script>

<div style="background-color: yellow;">    
  <h1 style="margin-top: 20px;margin-left: 40px;">Trending Products:</h1>    
  <?php
    $sql="SELECT P_ID, COUNT(*) FROM orderproducts GROUP BY P_ID ORDER BY COUNT(*) DESC LIMIT 4";
    $result=mysqli_query($conn,$sql);
    while ($row=mysqli_fetch_assoc($result)) {
      $id=$row['P_ID'];
      $sql="SELECT * FROM productdetails WHERE P_ID='$id'";
      $result2=mysqli_query($conn,$sql);
      while ($row2=mysqli_fetch_assoc($result2)) {
        $id=$row2['P_ID'];
        $dest="admin/includes/".$row2['P_Image']; 
        ?>
        <a style="text-decoration: none;color: black;" href="productdetails.php?id=<?php echo $id; ?>">
        <div style="width: 200px;height: 350px;margin: 40px;display: inline-block;padding: 25px;background-color: #f44336;" class="productdispay">
        <img src="<?php echo $dest; ?>" width="200px" height="250px">
        <p class="name" ><?php echo strtoupper($row2['P_Brand'])." ".strtoupper($row2['P_Name']); ?></p>
        <b><p class="price">Rs. <?php echo $row2['P_Price']; ?></p></b>
        <!--<a href="<?php echo $url; ?>" class="cart"><i class="fas fa-shopping-cart fa-2x" title="Add to wishlist"></i></a>-->
        <a href="" class="heart" ><i class="far fa-heart fa-2x" title="Add to wishlist"></i></a>
        </div>
        </a>  
    <?php
      }
    }?>
</div>  

<?php   require_once 'includes/footer.php'; ?>
</body>
</html>
