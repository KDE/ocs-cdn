<html>
<head>
    <title>OSC CDN Setup</title>
</head>
<body>

<?php

if (file_exists('config.php')) {
    require_once('config.php');
}

if (!isset($config['privateKey'])) :
    $configKey = md5(rand()) . md5(rand());

    ?>
    <h1>Private Key</h1>

    <b>config.php does not exist, or does not define a private key</b><br/>

    Copy and paste the below text into a config.php file. Once you have created
    the config file, you will also need to copy and paste the private key into
    your OSC Webserver configuration file.<br/><br/>

    <textarea style="width: 100%; height: 200px;">
&lt;?php

	if (!defined('APPLICATION_PATH'))
		die('This file is not publicly accessible.');

	$config = [
		'privateKey' => '<?= $configKey ?>'
	];

/* End of File */</textarea>

    <br/><br/><br/>
    <b>OCS Webserver application.ini config value</b>
    <br/>
    images.media.privateKey = &quot;<?= $configKey ?>&quot;

<?php
else:
    ?>
    <h1>Information</h1>

    Everything is up and running! For the private key please refer to the config.php file.
<?php
endif;
?>
</body>
</html>
