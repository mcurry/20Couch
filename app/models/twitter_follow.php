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
App::import('Model', 'TwitterBase');

class TwitterFollow extends TwitterBase {
	var $name = "TwitterFollow";
	
	function __findUpdate($options=array()) {
		$options = array_merge(array('action' => 'follow', 'limit' => 200), $options);
		return parent::find('all', $options);
	}
}
?>