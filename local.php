#!/usr/bin/php
<?php

set_time_limit(0);
error_reporting(0);

// Pleasse check line 22 and edit your minerid

// Put in your MYSQL settings
$servername = "";
$username = "";
$password = "";
$dbname = "evergreen";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

			$minerid = "f01240"; // Edit this to reflect your minerid
      $cmd2 = "/usr/bin/curl -sLH \"Authorization: $( /usr/bin/fil-spid.bash " . $minerid . " )\" 'https://api.evergreen.filecoin.io/eligible_pieces/sp_local?limit=5000'";
      $basic = shell_exec($cmd2);
      $result = json_decode($basic);

      // This foreach loops need work. It is a simple attempt to retrieve.
      foreach($result as $val => $elem)  {
        foreach($elem as $val2 => $val1) {
          if ($val1->sources[0]->deal_id != '') {
            echo "Deal is " . $val1->sources[0]->deal_id . " \n";
            $deal = $val1->sources[0]->deal_id;
            $deal = addslashes($deal);
            //echo "Provider is " . $val1->sources[0]->provider_id . " \n";
            $provider = $val1->sources[0]->provider_id;
            $provider = addslashes($provider);
          }

          if ($val1->sources[0]->sample_retrieve_cmd != '') {
            $srtc2 = $val1->sources[0]->sample_retrieve_cmd;
            $srtc2 = str_replace('lotus', '/usr/local/bin/lotus', $srtc2);
            //echo "Found Sample Retrievt CMD " . $srtc2 . " \n";
            $srtc = addslashes($srtc2);
          }

          if ($val1->sample_request_cmd != '') {
            $srqc2 = $val1->sample_request_cmd;
            //echo "Found Sample Request CMD " . $srqc2 . " \n";
            $srqc = addslashes($srqc2);
          }

          $newdeal="INSERT INTO `deals` (`id`, `deal_id`, `provider`, `sample_retrieve_cmd`, `sample_request_cmd`) VALUES (NULL, '$deal', '$provider', '$srtc', '$srqc');";
          $result = mysqli_query($conn, $newdeal);
          //echo "Debug SQL IS " . $newdeal . " \n";

			// Make a blocklist. I got this from the evergreen slack. It is good to check these once in a while to become alive.
			$blocked = array(
				"f0116436",
				"f0116445",
				"f0118317",
				"f0118330",
				"f01231",
				"f0134516",
				"f0134518",
				"f0153176",
				"f016398",
				"f019002",
				"f019362",
				"f021479",
				"f0218293",
				"f023530",
				"f0400135",
				"f0401135",
				"f0402371",
				"f0407733",
				"f0409172",
				"f0429063",
				"f0440182",
				"f0440208",
				"f0447183",
				"f0455466",
				"f0471691",
				"f0508328",
				"f053229",
				"f063869",
				"f066102",
				"f066259",
				"f0673920",
				"f070932",
				"f089380",
				"f087256"
			);

      if (!in_array($provider, $blocked)) {
        $minercheck="SELECT * from miners WHERE `provider` = '$provider' AND `ok` = '1'";
        $result = mysqli_query($conn, $minercheck);
        //echo "Debug SQL IS " . $newdeal . " \n";
        if (mysqli_num_rows($result) != '1') {
          //echo 'found blocked miner, skip';
        } else {
          // Check if the deal is already downloaded. If so do not write another sh file to download it again.
          $newdeal="SELECT `deal_id` FROM `deals` WHERE fetched is '1'";
          $result = mysqli_query($conn, $newdeal);
          if (mysqli_num_rows($result) > 0) {
            //echo 'found record dont download again';
          } else {
            $cmd3 ="#!/bin/bash \n";
            $cmd3 .= 'nohup ' . $srtc2 . " 2>&1 & \n";
            if ($provider != '') {
              $file2 = '/root/evergreen/get/' . $provider . "-" . $deal . '.sh';
              file_put_contents($file2, $cmd3);
              $filenamesr2 = shell_exec('chmod +x ' . $file2);
            }
          }
        }
      }
    }
  }
?>
