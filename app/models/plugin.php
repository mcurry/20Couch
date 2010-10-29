<?php
/***************************************************************************
 20Couch

 Copyright (c) 2009-2010 Matt Curry

 @author      Matt Curry <matt@pseudocoder.com>
 @license     MIT
 
 More info at: http://www.20couch.com
****************************************************************************/
?>
<?php
class Plugin extends AppModel {
	var $name = 'Plugin';
	var $order = array('Plugin.name' => 'asc');

	function load($path) {
		
		$pluginClass = Inflector::camelize($path);
		App::import('File', $pluginClass, array('file' => Configure::read('Plugin.path') . DS . $path . DS . $path . '.php'));
		return new $pluginClass($path);
	}
	
	function __findActive() {
		return parent::find('all', array('conditions' => array('Plugin.active' => true)));
	}
}
?>