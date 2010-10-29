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
class AppController extends Controller {
	var $helpers = array('Session', 'Html', 'Form', 'AppForm', 'Js', 'Site');
	var $components = array('Session', 'Auth', 'RequestHandler');

	function beforeFilter() {
		Configure::write('Config.language', 'en');

		if ($this->RequestHandler->isAjax()) {
			Configure::write('debug', 0);
		}

		$this->Auth->fields = array('username' => 'username', 'password' => 'password');
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
		$this->Auth->loginRedirect = array('controller' => 'providers', 'action' => 'dashboard');
		$this->Auth->ajaxLogin = 'ajax_login';
		$this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
		$this->Auth->autoRedirect = false;

		if ($user = $this->Auth->user()) {
			App::import('Model', 'User');
			User::store($this->Auth->user());
		}
		
		Configure::write('Twitter.apiRequestsRemaining', '?') ;
	}
	
	function beforeRender() {
		$this->set('title_for_layout', '20Couch');

		if ($this->RequestHandler->isMobile()) {
			$this->set('mobile', true);
		} else {
			$this->set('mobile', false);
		}

		//check for plugins
		if ($this->layout == 'default') {
			$Plugin = ClassRegistry::init('Plugin');
			$plugins = $Plugin->find('active');
			$pluginClasses = array();
			foreach($plugins as $plugin) {
				$pluginClasses[$plugin['Plugin']['path']] = $Plugin->load($plugin['Plugin']['path']);
			}

			$this->set('plugins', $pluginClasses);
		} else {
			$this->set('plugins', array());
		}
	}
}
?>