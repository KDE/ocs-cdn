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

header("HTTP/1.1 503 Service Unavailable");
exit(1);

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

$imgurl = $_GET['path'];
$imagename = basename($imgurl);

if (!$imgurl || !$imagename) {
    print "Error";

    return;
}


if (file_exists('./img/' . $imagename)) {
    print "<img src='/cache/800x600-2/img/" . $imagename . "'>";

    return;
}
$image = getimg($imgurl);
file_put_contents('img/' . $imagename, $image);

if (file_exists('./img/' . $imagename)) {
    print "<img src='/cache/800x600-2/img/" . $imagename . "'>";
} else {
    print "Error";
}
