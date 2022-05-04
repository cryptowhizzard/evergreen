#!/usr/bin/php
<?php

set_time_limit(0);
error_reporting(0);

// Please edit the path settings on line 22.
// Edit your MYSSQL settings
$servername = "";
$username = "";
$password = "";
$dbname = "evergreen";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
	echo "Mysql error";
	die("Connection failed: " . mysqli_connect_error());
}

$files = scandir('/root/evergreen/get/');
foreach ($files as $file) {
	//echo $file . "\n";
	if (strstr($file, '.car')) {
		//file found
		$filewithpercent = "%" . $file . "%";
		$updatedeal ="UPDATE `deals` SET `fetched` = '1' WHERE `sample_retrieve_cmd` LIKE '$filewithpercent'";
		$result = mysqli_query($conn, $updatedeal);
	}
}
?>
