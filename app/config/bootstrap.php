<?php
/* SVN FILE: $Id$ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 *
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php is loaded
 * This is an application wide file to load any function that is not used within a class define.
 * You can also use this to include or require any files in your application.
 *
 */
/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * $modelPaths = array('full path to models', 'second full path to models', 'etc...');
 * $viewPaths = array('this path to views', 'second full path to views', 'etc...');
 * $controllerPaths = array('this path to controllers', 'second full path to controllers', 'etc...');
 *
 */

if(Configure::read('debug')) {
	Configure::write('20Couch.home', 'http://20couch');
} else {
	Configure::write('20Couch.home', 'http://20couch.com');
}
Configure::write('Update.file', '20couch-latest.zip');

Configure::write('Version', '1.13');
Configure::write('staticAssets', false);
Configure::write('demo', false);

$services = array(1 => 'TwitterAccount', 2 => 'TwitterSearch', 3 => 'TwitterFollow', 4 => 'TwitterReply');
Configure::write('Services', $services);
Configure::write('ServicesName', array_flip($services));

Configure::write('UpdateLimit', 200);
Configure::write('UpdateMaxPage', 5);

Configure::write('UpdateFrequencies', array(60 => __('1 minute', true),
								 300 => __('5 minutes', true),
								 3600 => __('1 hour', true),
								 21600 => __('6 hours', true),
								 86400 => __('1 day', true)));


Configure::write('Plugin.path', APP . 'plugins');

define('REQUEST_MOBILE_UA', '(android|webOS|iPhone|MIDP|AvantGo|BlackBerry|J2ME|Opera Mini|DoCoMo|NetFront|Nokia|PalmOS|PalmSource|portalmmm|Plucker|ReqwirelessWeb|SonyEricsson|Symbian|UP\.Browser|Windows CE|Xiino)');

if (!function_exists('hash_hmac')) {
	//taken from http://www.php.net/manual/en/function.hash-hmac.php#93440
	//Thanks KC Cloyd
	function hash_hmac($algo, $data, $key, $raw_output = false) {
		$algo = strtolower($algo);
		$pack = 'H'.strlen($algo('test'));
		$size = 64;
		$opad = str_repeat(chr(0x5C), $size);
		$ipad = str_repeat(chr(0x36), $size);

		if (strlen($key) > $size) {
			$key = str_pad(pack($pack, $algo($key)), $size, chr(0x00));
		} else {
			$key = str_pad($key, $size, chr(0x00));
		}

		for ($i = 0; $i < strlen($key) - 1; $i++) {
			$opad[$i] = $opad[$i] ^ $key[$i];
			$ipad[$i] = $ipad[$i] ^ $key[$i];
		}

		$output = $algo($opad.pack($pack, $algo($ipad.$data)));

		return ($raw_output) ? pack($pack, $output) : $output;
	}
}

//EOF
?>