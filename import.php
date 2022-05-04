#!/usr/bin/php
<?php

set_time_limit(0);
error_reporting(0);

// Please update f01240 to reflect your miner!
// Please update line 28 ( Max tasks of 12 ) to your situation.
// Please update line 70, 84 and 91 to whereever you put the evergreen dir.

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

$intb = (int)$lines_stringb;
if ($intb > '12') { echo "Miner overloaded - Wait \n"; exit; }

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

      $cmd2 = "echo /usr/bin/curl -sLH \\\"Authorization: $( ./fil-spid.bash f01240 )\\\" 'https://api.evergreen.filecoin.io/pending_proposals' | sh ";
			echo "We try command " . $cmd2 . "\n";
			$basic = shell_exec($cmd2);
                        $result = json_decode($basic);

                        foreach($result as $val => $elem)  {

                                foreach($elem as $val4 => $val3) {
                                foreach($val3 as $val2 => $val1) {

				if ($val1->sources[0]->piece_cid != '') {
				echo "piece_cid is " . $val1->sources[0]->piece_cid . " \n";
				$piece_cid = $val1->sources[0]->piece_cid;
				echo "sample_import_cmd is " . $val1->sources[0]->sample_import_cmd . " \n";
				$sample_import_cmd = $val1->sources[0]->sample_import_cmd;
				}

				if ($val1->sources[0]->sample_retrieve_cmd != '') {
                        	$srtc2 = $val1->sources[0]->sample_retrieve_cmd;
				$srtc2 = str_replace('lotus', '/usr/local/bin/lotus', $srtc2);
				echo "Found Sample Retrievt CMD " . $srtc2 . " \n";
				$srtc = addslashes($srtc2);
				}

			if ($val1->piece_cid != '') {
                        $piece_cid = $val1->piece_cid;
			echo "Found piece_cid " . $piece_cid . " \n";
                        $sample_import_cmd = $val1->sample_import_cmd;
			echo "Found sample_import_cmd " . $sample_import_cmd . " \n";
			}

			// Put our path extra with the import command or alter it to whatever you want
			//$sample_import_cmd = str_replace("baga", "$(pwd)/get/baga", $sample_import_cmd);
			$sample_import_cmd = str_replace("baga", "/root/evergreen/get/baga", $sample_import_cmd);
			$sample_import_cmd = str_replace("lotus", "/usr/local/bin/lotus", $sample_import_cmd);

			// Check if we have not already imported this one
			$piece = "%" . $piece_cid . "%";
			if ($sample_request_cmd == '') $sample_request_cmd = "void"; // Use this to make the LIKE query below safe. Otherwise empty ones will mess up
			$newdeal = "SELECT * FROM deals WHERE `sample_request_cmd` LIKE '$piece' AND imported = '1'";
			$result = mysqli_query($conn, $newdeal);
                        if (mysqli_num_rows($result) > 0) {
                                echo "Found record dont download again \n";
                                } else {
			                               $cmd3 ="#!/bin/bash \n";
                	                    $cmd3 .= $sample_import_cmd . "\n";
			                                $file2 = '/root/evergreen/import.sh';
                	                     file_put_contents($file2, $cmd3);
			                                 $filenamesr2 = shell_exec('chmod +x ' . $file2 . ' && sudo ' . $file2);
			                                    // Delete the car file when done to avoid wasting disk space
			                                          $sic1 = explode('/root',$sample_import_cmd);
			                                             $sic2 = end($sic1);
			                                                $sic2 = str_replace('\"', '', $sic2); // Remove the final "
			                                                   unlink($sic2);
			                                                      $newdeal2="UPDATE `deals` SET `imported` = '1' WHERE `sample_request_cmd` LIKE '$piece'";
                                                            $result2 = mysqli_query($conn, $newdeal2);
                                                            echo "Debug SQL IS " . $newdeal2 . " \n";
                                                          }
                                                        }
                                                      }
                                                    }
?>
