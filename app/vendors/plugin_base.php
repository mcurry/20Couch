<?php
class PluginBase {
	var $path = null;
	var $author = null;
	var $link = null;
	
	function __construct($path) {
		$this->path = $path;
	}
	
	function js() {
		return false;
	}
	
	function css() {
		return false;
	}
}
?>