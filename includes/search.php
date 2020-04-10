<?php
	require_once("dbConn.php");
	$search=$_GET['search'];
	$id = array();
	$sql="SELECT * FROM productdetails WHERE P_Name LIKE '%$search%' OR P_Brand LIKE '%$search%' OR P_Category LIKE '%$search%'";
	$result=mysqli_query($conn, $sql);
	while ($row=mysqli_fetch_assoc($result)) {
		echo $row['P_ID']."  ".$row['P_Name']." ".$row['P_Category']." ".$row['P_Brand']."<br>";
	}

?>