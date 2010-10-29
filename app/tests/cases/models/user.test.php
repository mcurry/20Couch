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
App::import('Core', 'Security');
App::import('Model', 'User');

class UserTestCase extends CakeTestCase {
	var $User = null;
	var $fixtures = array('app.user');

	function startTest() {
		$this->User =& ClassRegistry::init('User');
	}

	function testUserInstance() {
		$this->assertTrue(is_a($this->User, 'User'));
	}
	
	function testValidateOneUser() {
		$result = $this->User->__validateOneUser();
		$this->assertFalse($result);
		
		$this->User->delete(1);
		$result = $this->User->__validateOneUser();
		$this->assertTrue($result);
	}

	function testValidatePasswordEmpty() {
		$this->User->data = array('User' => array('password' => Security::hash('password1', null, true)));
		$result = $this->User->__validatePasswordEmpty();
		$this->assertTrue($result);

		$this->User->data = array('User' => array('password' => Security::hash('', null, true)));
		$result = $this->User->__validatePasswordEmpty();
		$this->assertFalse($result);
	}

	function testValidatePasswordMatch() {
		$this->User->data = array('User' => array('password' => Security::hash('password1', null, true),
																			'verify_password' => 'password1'));
		$result = $this->User->__validatePasswordMatch();
		$this->assertTrue($result);

		$this->User->data = array('User' => array('password' => Security::hash('password1', null, true),
																			'verify_password' => 'password2'));
		$result = $this->User->__validatePasswordMatch();
		$this->assertFalse($result);
	}
}
?>