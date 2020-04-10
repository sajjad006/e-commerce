<?php

if (isset($_POST['submit'])) {
	include_once '../includes/dbConn.php';

	if (isset($_POST['keyword'])) {

		$keyword = $_POST['keyword'];	
		echo $keyword."<br>";
		$keyword = explode(' ', $keyword);

		for ($i=0; $i < count($keyword); $i++) { 
			$keyword[$i] = metaphone($keyword[$i]);
		}

		$result = implode(" ", $keyword);
		$id = array();

		$sql = "SELECT * FROM productdetails WHERE Indexing LIKE '%$result%'";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) < 1) {
			foreach ($keyword as $key) {
				$word = metaphone($key);
				$sql = "SELECT * FROM productdetails WHERE Indexing LIKE '%$word%'";
				$result = mysqli_query($conn, $sql);

				if (mysqli_num_rows($result)) {
					while ($row=mysqli_fetch_assoc($result)) {
						if (!in_array($row['P_ID'], $id)) {
							array_push($id, $row['P_ID']);
						}
					}
				}
			}
		}	
		else{
			while ($row = mysqli_fetch_assoc($result)) {
				if (!in_array($row['P_ID'], $id)) {
					array_push($id, $row['P_ID']);
				}
			}
		}

		print_r($id);
		echo "<br>".count($id);
	}
	else{
		header('Location:search_page.php');
		exit();
	}
}
else{
	header('Location:search_page.php');
	exit();
}