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

/*
function getimg($url)
{
    #$headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';              
    $headers[] = 'Connection: Keep-Alive';
    $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
    $user_agent = 'php';
    $process = curl_init($url);
    curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_USERAGENT, $user_agent);
    curl_setopt($process, CURLOPT_TIMEOUT, 30);
    curl_setopt($process, CURLOPT_USERPWD, "ubuntu:ubuntu.");
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
    $return = curl_exec($process);
    curl_close($process);
    return $return;
}
 
 * 
 */

//$CN_APTH = "/mnt/volume-fra1-11/var/www/cdn/pling-cdn";
$CN_APTH = ".";

//echo "Test-URL". urlencode('8/d/9/f/b3eae1990699459eac56beb682b5e79f45e0.png');


$imgurl = urldecode($_GET['path']);

echo "<p>Deleting file: ".$imgurl.'</p>';

$delete_post_name = $_GET['post'];
$imagename = basename($imgurl);

if (!$imgurl || !$imagename) {
    print "Error, param: path missing";
    return;
}
if (!$delete_post_name) {
    print "Error, param: post missing";
    return;
}

$fileExists = file_exists($CN_APTH.'/img/' . $imgurl);

print_r("<p>File exists: ".$CN_APTH.'/img/' . $imgurl.' = ' . $fileExists);

if($fileExists) {
    echo '<img src="'.$CN_APTH.'/img/' . $imgurl .'">';
}

print_r("<p>Rename file...");
print_r('<p>cp '.$CN_APTH.'/img/' . $imgurl . ' ' . $CN_APTH.'/img/' . $imgurl . $delete_post_name);

//$last_line = system('mv '.$imgurl . ' ' . $imgurl . $delete_post_name, $retval);
//TODO
$last_line = system('cp '.$CN_APTH.'/img/' . $imgurl . ' ' . $CN_APTH.'/img/' . $imgurl . $delete_post_name);
echo $last_line;

$fileExists = file_exists($CN_APTH.'/img/' . $imgurl . $delete_post_name);

print_r("<p>New File exists: ".$CN_APTH.'/img/' . $imgurl . $delete_post_name.' = ' . $fileExists);

if($fileExists) {
    echo '<img src="'.$CN_APTH.'/img/' . $imgurl . $delete_post_name.'">';
}

//TODO
print_r("<p>Search for cached files: ");
print_r('<p>locate -i "' . $imgurl.'"');

$last_line = exec('locate -i "' . $imgurl.'"', $resultArray, $result);
//var_dump($resultArray);

foreach ($resultArray as $value) {
    echo "<p>rm $value";
}


/*
$image = getimg($imgurl);
file_put_contents('img/' . $imagename, $image);

if (file_exists('./img/' . $imagename)) {
    print "<img src='/cache/800x600-2/img/" . $imagename . "'>";
} else {
    print "Error";
}

*/