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


// Ensure library/ is on include_path
set_include_path(implode("/", array(
    APPLICATION_PATH . '/../../library',
    get_include_path(),
)));


require_once 'Zend/Loader/Autoloader.php';

$loader = Zend_Loader_Autoloader::getInstance();
$loader->setFallbackAutoloader(true);

echo "<pre>";
echo "start upload test picture\n";

$client = new Zend_Http_Client('http://localhost/image_bucket_upload.php');
if (false === file_exists(APPLICATION_PATH . '/f5463b.png')) {
    echo "file not found.";
    exit(0);
}
$client->setFileUpload(APPLICATION_PATH . '/f5463b.png', 'f5463b.png', NULL, 'jpg');
//$client->setConfig(array('timeout'      => 30));
//$client->setParameterPost('XDEBUG_SESSION_START', 'PHPSTORM');
//$client->setAuth('ubuntu', 'ubuntu.', Zend_Http_Client::AUTH_BASIC);

$response = $client->request('POST');

echo $response->getBody()."\n";
//print_r($client->getLastRequest());
//print_r($client->getLastResponse());
echo "</pre>";