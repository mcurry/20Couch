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
class User extends AppModel {

	var $name = 'User';
	var $validate = array(
											'username' => array('required' => array(
																													'rule' => 'notEmpty',
																													'required' => true,
																													'allowEmpty' => false,
																													'last' => true
																											),
																					'one_user' => array('rule' => '__validateOneUser')
																				 ),
											'password' => array('required' => array(
																													'rule' => 'notEmpty',
																													'allowEmpty' => false,
																													'required' => true,
																													'last' => true),
																					'blank_password' => array(
																																'rule' => '__validatePasswordEmpty',
																																'last' => true),
																					'verify_password' => array('rule' => '__validatePasswordMatch')
																				 )
									);


	function __validateOneUser() {
		if (parent::find('count')) {
			return false;
		}

		return true;
	}

	function __validatePasswordEmpty() {
		App::import('Core', 'Security');
		$hashed = Security::hash('', null, true);

		if ($hashed == $this->data['User']['password']) {
			return false;
		}

		return true;
	}

	function __validatePasswordMatch() {
		App::import('Core', 'Security');
		if ($this->data['User']['password'] != Security::hash($this->data['User']['verify_password'], null, true)) {
			$this->invalidate('verify_password', 'verify_password');
			return false;
		}

		return true;
	}


	/************************************************************** Static User Functions **************************************************************/
	function &getInstance($user=null) {
		static $instance = array();

		if ($user) {
			$instance[0] =& $user;
		}

		if (!$instance) {
			trigger_error(__("User not set.", true), E_USER_WARNING);
			return false;
		}

		return $instance[0];
	}

	function store($user) {
		if (empty($user)) {
			return false;
		}

		User::getInstance($user);
	}

	function get($path) {
		$_user =& User::getInstance();

		$path = str_replace('.', '/', $path);
		if (strpos($path, 'User') !== 0) {
			$path = sprintf('User/%s', $path);
		}

		if (strpos($path, '/') !== 0) {
			$path = sprintf('/%s', $path);
		}

		$value = Set::extract($path, $_user);

		if (!$value) {
			return false;
		}
		
		$value = $value[0];
		
		$key = array_pop(explode('/', $path));
		if(is_array($value) && isset($value[$key])) {
			return $value[$key];
		}

		return $value;
	}

}
?>