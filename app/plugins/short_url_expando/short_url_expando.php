<?php
App::import('Vendor', 'PluginBase');

class ShortUrlExpando extends PluginBase {
	var $name = "Short Url Expando";
	var $description = "Hover over a shortened url to see the actual destination.  Only bit.ly support at the moment.  More to come";
	var $author = '20Couch';
	var $link = '20couch.com';
	var $version = '1.0';
	
	function js() {
		return array('/short_url_expando/js/short_url_expando.js');
	}
	
	function css() {
		return array('/short_url_expando/css/short_url_expando.css');
	}
}
?>