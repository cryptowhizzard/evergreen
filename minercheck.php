#!/usr/bin/php
<?php

set_time_limit(0);
error_reporting(0);

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

	$updatedeal3 ="SELECT deal_id, provider FROM deals GROUP BY(provider)";
	$result3 = mysqli_query($conn, $updatedeal3);
  while ($row = mysqli_fetch_array($result3)) {
    $provider = $row['provider'];
    $updatedeal ="INSERT IGNORE INTO `miners` (`id`, `provider`) VALUES (NULL, '$provider')  ";
    $result = mysqli_query($conn, $updatedeal);

    // Check if this thing is alive
    $src = shell_exec("/usr/bin/timeout 10 /usr/local/bin/lotus client query-ask $provider");

    // We are looking for this string: Verified Price per GiB: 0 FIL
    if (strstr($src, 'Verified Price per GiB: 0 FIL')) {
	     // Update the database to refect the deal
       $updatedeal ="UPDATE `miners` SET ok = '1' WHERE `provider` = '$provider'";
       $result = mysqli_query($conn, $updatedeal);
     } else {
       $updatedeal ="UPDATE `miners` SET ok IS NULL WHERE `provider` = '$provider'";
       $result = mysqli_query($conn, $updatedeal);
     }
   }
?>
