<?php
App::import('Vendor', 'PluginBase');

class BossKeyRoulette extends PluginBase {
	var $name = "Boss Key Roulette";
	var $description = "Press F9 to replace 20Couch with Google.com 99% of the time.  The other 1%?  Porn.  Hardcore.  Girl-on-girl-on-guy-on-bike-with-cup porn.";
	var $author = '20Couch';
	var $link = '20couch.com';
	var $version = '1.0';
	
	function js() {
		return array('/boss_key_roulette/js/boss_key_roulette.js');
	}
}
?>