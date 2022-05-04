#!/usr/bin/php
<?php
set_time_limit(0);

// Watch for line 47. You need to edit your MinerID there!. Line 49 en 52 need proper path.
//MYSQL settings
$servername = "";
$username = "";
$password = "";
$dbname = "evergreen";

// I use this code to see how many PC1 tasks the miner has open. This can be used together with Farcaster. See autopledge.sh
$url = 'http://<YOURIPADRES/status.html';
$ch=curl_init();
$timeout=5;
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$lines_stringb=curl_exec($ch);
curl_close($ch);
//

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  echo "Mysql error";
  die("Connection failed: " . mysqli_connect_error());
}

$updatedeal ="select * from `deals` WHERE `fetched` = '1' AND proposed IS NULL LIMIT 1";
$result = mysqli_query($conn, $updatedeal);

while( $row1 = mysqli_fetch_array( $result ) ) {
  $id = $row1['id'];
  $did = $row1['deal_id'];
  $provider = $row1['provider'];
  $srtc = $row1['sample_retrieve_cmd'];
  $src = $row1['sample_request_cmd'];
  $fetched = $row1['fetched'];
  $proposed = $row1['proposed'];

  // Write to make the propals
	$cmd2 ="#!/bin/bash \n";
	// We need to re-add some backslashes for Riba's script ... Mysql wiped them out with addslashes
	$src = str_replace("\"Autho", "\\\"Autho", $src);
	$src = str_replace("f01240 )\"", "f01240 )\\\"", $src);
	$cmd2 .= $src;
        $file2 = '/root/evergreen/proposeexec.sh';
	file_put_contents($file2, $cmd2);
        //$filenamesr2 = shell_exec('chmod +x /root/evergreen/proposeexec.sh && sudo /root/evergreen/proposeexec.sh');
        $filenamesr2 = passthru('chmod +x /root/evergreen/proposeexec.sh && sudo /root/evergreen/proposeexec.sh');
	       echo  $filenamesr2 . " proposed result \n";
         // Update the database to refect the deal
         $updatedeal ="UPDATE `deals` SET proposed = '1' WHERE `id` = '$id'";
         $result = mysqli_query($conn, $updatedeal);
       }
?>
