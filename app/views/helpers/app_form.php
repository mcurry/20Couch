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
class AppFormHelper extends FormHelper {

	function input($fieldName, $options = array()) {
		if(empty($options['div'])) {
			$options['div'] = array();
		}
		$options['div']['data-role'] = 'fieldcontain';
		
		if(!empty($options['type']) && $options['type'] == 'checkbox') {
			$options['before'] = '<fieldset data-role="controlgroup">';
			$options['after'] = '</fieldset>';
		}
		
		return parent::input($fieldName, $options);
	}
}
?>