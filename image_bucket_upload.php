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

// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__)));


// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

defined('IMAGES_UPLOAD_PATH')
|| define('IMAGES_UPLOAD_PATH', APPLICATION_PATH . '/img/');


// Ensure library/ is on include_path
set_include_path(implode("/", array(
    APPLICATION_PATH . '/../library',
    get_include_path(),
)));

$_mime_type = array(
    'image/jpeg' => '.jpg',
    'image/jpg' => '.jpg',
    'image/png' => '.png',
    'image/gif' => '.gif',
    'application/x-empty' => '.png'
);


require_once 'Zend/Loader/Autoloader.php';

$loader = Zend_Loader_Autoloader::getInstance();
$loader->setFallbackAutoloader(true);

$log = new Zend_Log();
$writer = new Zend_Log_Writer_Stream(APPLICATION_PATH .'/data/logs/msg_' . date("Y-m-d") . '.log');
$log->addWriter($writer);

$log->debug('_POST: ' . print_r($_POST, true));
$log->debug('_FILES: ' . print_r($_FILES, true));

$upload = new Zend_File_Transfer_Adapter_Http();
$upload->addValidator('Count', false, 1)
    ->addValidator('IsImage', false)
    ->addValidator('Size', false, 10097152)
    ->addValidator('FilesSize', false, 10097152);

//$upload->addValidator('ImageSize', false,
//    array(
//        'minwidth' => 50,
//        'maxwidth' => 2000,
//        'minheight' => 50,
//        'maxheight' => 2000
//    )
//);

if (false === $upload->isValid()){
    $log->err('isValid errors: ' . print_r($upload->getErrors(), true));
    $log->info('isValid messages: ' . print_r($upload->getMessages(), true));

    header("HTTP/1.0 500 Server Error");
    print implode("\n<br>", $upload->getMessages());
    exit(0);
}

//create buckets
$fileHash = $upload->getHash('sha1').''. md5(rand());
$destBucketPath = substr_replace($fileHash, '/', 1, 0);
$destBucketPath = substr_replace($destBucketPath, '/', 3, 0);
$destBucketPath = substr_replace($destBucketPath, '/', 5, 0);
$destBucketPath = substr_replace($destBucketPath, '/', 7, 0);
$destPath = IMAGES_UPLOAD_PATH . $destBucketPath . $_mime_type[$upload->getMimeType()];

$dir = dirname($destPath);
if (!file_exists($destPath) and !is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$upload->addFilter('Rename', array('target' => $destPath, 'overwrite' => true));

if (false === $upload->receive()) {
    $log->err('receive errors: ' . print_r($upload->getErrors(), true));
    $log->info('receive messages: ' . print_r($upload->getMessages(), true));

    header("HTTP/1.0 500 Server Error");
    print implode("\n<br>", $upload->getMessages());
    exit(0);
}

header("HTTP/1.0 200 OK");
print $destBucketPath . $_mime_type[$upload->getMimeType()];
