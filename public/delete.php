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

echo "<p>Deleting file: ".$imgurl.'</p>\n';

$delete_post_name = $_GET['post'];
$imagename = basename($imgurl);

if (!$imgurl || !$imagename) {
    echo "Error, param: path missing\n";
    return;
}
if (!$delete_post_name) {
    echo "Error, param: post missing\n";
    return;
}

$fileExists = file_exists($CN_APTH.'/img/' . $imgurl);



if($fileExists) {
    echo("<p>File exists".'\n');
    echo '<img src="'.$CN_APTH.'/img/' . $imgurl .'">';
} else {
    echo("<p>File did not exists".'\n');
}

echo("<p>Rename file...".'\n');
echo('<p>Command: cp '.$CN_APTH.'/img/' . $imgurl . ' ' . $CN_APTH.'/img/' . $imgurl . $delete_post_name.'\n');

//TODO
//$last_line = system('mv '.$imgurl . ' ' . $imgurl . $delete_post_name, $retval);
$last_line = system('cp '.$CN_APTH.'/img/' . $imgurl . ' ' . $CN_APTH.'/img/' . $imgurl . $delete_post_name.' 2>&1');
echo $last_line.'\n';

$fileExists = file_exists($CN_APTH.'/img/' . $imgurl . $delete_post_name);

if($fileExists) {
    echo("<p>File exists".'\n');
    echo '<img src="'.$CN_APTH.'/img/' . $imgurl . $delete_post_name .'">';
} else {
    echo("<p>File did not exists".'\n');
}

//TODO
echo("<p>Search for cached files: \n");
echo('<p>Command: locate -i "' . $imgurl.'"\n');

$last_line = exec('locate -i "' . $imgurl.'" 2>&1', $resultArray, $result);
//var_dump($resultArray);

foreach ($resultArray as $value) {
    if(strpos($value, '/cache/') !== false) {
        echo "<p>Command: rm $value\n";
    }
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