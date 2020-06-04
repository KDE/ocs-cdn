<?php
/**
 *  ocs-webserver
 *
 *  Copyright 2016 by pling GmbH.
 *
 *    This file is part of ocs-webserver.
 *
 *    This program is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU Affero General Public License as
 *    published by the Free Software Foundation, either version 3 of the
 *    License, or (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.
 **/

//Constants
//$CN_APTH = "/mnt/volume-fra1-11/var/www/cdn/pling-cdn";
$CN_APTH = ".";

$imgurl = urldecode($_GET['path']);

echo "<p>Deleting file: ".$imgurl.'</p>' . PHP_EOL;

$delete_post_name = $_GET['post'];
$imagename = basename($imgurl);

if (!$imgurl || !$imagename) {
    header("HTTP/1.0 500 Server Error");
    echo "Error, param: path missing" . PHP_EOL;
    return;
}
if (!$delete_post_name) {
    header("HTTP/1.0 500 Server Error");
    echo "Error, param: post missing" . PHP_EOL;
    return;
}

$fileExists = file_exists($CN_APTH.'/img/' . $imgurl);



if($fileExists) {
    echo("<p>File exists" . PHP_EOL);
    echo '<img src="'.$CN_APTH.'/img/' . $imgurl .'">' . PHP_EOL;
} else {
    echo("<p>File did not exists" . PHP_EOL);
}

echo("<p>Rename file..." . PHP_EOL);
echo('<p>Command: cp '.$CN_APTH.'/img/' . $imgurl . ' ' . $CN_APTH.'/img/' . $imgurl . $delete_post_name . PHP_EOL);

//TODO
//$last_line = system('mv '.$imgurl . ' ' . $imgurl . $delete_post_name, $retval);
$last_line = system('cp '.$CN_APTH.'/img/' . $imgurl . ' ' . $CN_APTH.'/img/' . $imgurl . $delete_post_name.' 2>&1');
echo $last_line . PHP_EOL;

$fileExists = file_exists($CN_APTH.'/img/' . $imgurl . $delete_post_name);

if($fileExists) {
    echo("<p>File exists" . PHP_EOL);
    echo '<img src="'.$CN_APTH.'/img/' . $imgurl . $delete_post_name .'">' . PHP_EOL;
} else {
    echo("<p>File did not exists" . PHP_EOL);
}

//TODO
echo("<p>Search for cached files:" . PHP_EOL);
echo('<p>Command: locate -i "' . $imgurl.'"' . PHP_EOL);

$last_line = exec('locate -i "' . $imgurl.'" 2>&1', $resultArray, $result);
//var_dump($resultArray);

foreach ($resultArray as $value) {
    if(strpos($value, '/cache/') !== false) {
        echo "<p>Command: rm $value" . PHP_EOL;
    }
}