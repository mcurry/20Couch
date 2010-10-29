<?php
App::import('Vendor', 'PluginBase');

class KeyboardNav extends PluginBase {
	var $name = "Keyboard Navigation";
	var $description = "Keyboard Navigation similar to Google Reader (only j/k item select works at the moment).";
	var $author = '20Couch';
	var $link = '20couch.com';
	var $version = '1.0';
	
	function js() {
		return array('/keyboard_nav/js/keyboard_nav.js');
	}
	
	function css() {
		return array('/keyboard_nav/css/keyboard_nav.css');
	}
}
?>