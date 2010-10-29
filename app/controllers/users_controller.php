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
class UsersController extends AppController {
	var $name = 'Users';
	var $layout = 'anony';

	function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->allow(array('install', 'logout'));
	}

	function login() {
		if ($user = $this->Auth->user()) {
			if (empty($this->data['User']['remember'])) {
				setcookie(Configure::read('Session.cookie'), $_COOKIE[Configure::read('Session.cookie')], 0, '/');
			} else {
				setcookie(Configure::read('Session.cookie'), $_COOKIE[Configure::read('Session.cookie')], time() + Configure::read('Session.timeout') * 100, '/');
			}

			$Setting = ClassRegistry::init('Setting');
			$this->Session->write('Auth.User.Setting', $Setting->find('load'));
			$this->redirect($this->Auth->loginRedirect);
		}
	}

	function logout() {
		$this->redirect($this->Auth->logout());
	}

	function install() {
		//if any user already exists...
		if ($this->User->find('count') > 0) {
			$this->render('already_installed');
		}

		if ($this->data) {
			if ($this->User->save($this->data)) {
				$this->Auth->login($this->data);
				$this->redirect('/');
			}
		}
	}
}
?>