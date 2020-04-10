<?php

include_once '../includes/dbConn.php';

$sql = "SELECT * FROM productdetails";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
	$id = $row['P_ID'];
	$name =  explode(' ', $row['P_Name']);
	$category = explode(' ', $row['P_Category']);
	$brand = explode(' ', $row['P_Brand']);

	$keyword = array_merge($name, $category, $brand);

	$index="";
	$length = count($keyword);
	
	for ($i=0; $i < $length; $i++) { 
		$index.= " ".metaphone($keyword[$i]);
	}
	
	$sql = "UPDATE productdetails SET Indexing='$index' WHERE P_ID='$id'";
	if(mysqli_query($conn,$sql)){
		echo "Successfully completed indexing <br>";
	}
	else{
		echo "sorry! Failed to complete indexing <br>";
	}
}