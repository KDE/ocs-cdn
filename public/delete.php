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

if (file_exists('config.php')) {
    require_once('config.php');
} else {
    header("HTTP/1.0 500 Server Error");
    echo "Error" . PHP_EOL;
    return;
}

$configKey = $config['privateKey'];


$privKey = urldecode($_GET['key']);

if (!$privKey || $privKey != $configKey) {
    header("HTTP/1.0 500 Server Error");
    echo "Error" . PHP_EOL;
    return;
}

$imgurl = urldecode($_GET['path']);

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

echo "Deleting file: ".$imgurl.PHP_EOL;

$fileExists = file_exists($CN_APTH.'/img/' . $imgurl);

if($fileExists) {
    echo("File exists" . PHP_EOL);
} else {
    echo("File did not exists" . PHP_EOL);
}

echo("Rename file..." . PHP_EOL);
echo('Command: cp '.$CN_APTH.'/img/' . $imgurl . ' ' . $CN_APTH.'/img/' . $imgurl . $delete_post_name . PHP_EOL);

//TODO
//$last_line = system('mv '.$imgurl . ' ' . $imgurl . $delete_post_name, $retval);
$last_line = system('cp '.$CN_APTH.'/img/' . $imgurl . ' ' . $CN_APTH.'/img/' . $imgurl . $delete_post_name.' 2>&1');
echo $last_line . PHP_EOL;

$fileExists = file_exists($CN_APTH.'/img/' . $imgurl . $delete_post_name);

if($fileExists) {
    echo("Rename File done" . PHP_EOL);
} else {
    header("HTTP/1.0 500 Server Error");
    echo("Rename File did not work!" . PHP_EOL);
}

//TODO
echo("Search for cached files:" . PHP_EOL);
echo('Command: locate -i "' . $imgurl.'"' . PHP_EOL);

$last_line = exec('locate -i "' . $imgurl.'" 2>&1', $resultArray, $result);

if(count($resultArray) == 0) {
    echo "No cached files found." . PHP_EOL;
}

foreach ($resultArray as $value) {
    if(strpos($value, '/cache/') !== false) {
        echo "Command: rm $value" . PHP_EOL;
    }
}