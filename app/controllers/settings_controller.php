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
class SettingsController extends AppController {
	var $name = 'Settings';
	var $layout = 'settings';
	
	function index() {
		if ($this->data) {
			if ($this->Setting->saveAll($this->data)) {
				$this->Session->write('Auth.User.Setting', $this->data['Setting']);
				$this->Session->setFlash(__('Saved', true));

				if(!empty($this->data['Setting']['language'])) {
					Configure::write('Config.language', $this->data['Setting']['language']);
				}
			}
		}

		$settings = $this->Setting->find('editable');
		$retweetMethods = array('manual' => 'Old School',
														 'api' => 'New School');
		$showRemainingRequests = array('0' => 'No',
														 '1' => 'Yes');
		$this->set(compact('settings', 'retweetMethods', 'showRemainingRequests'));
	}
	
	function update() {
		if($this->data) {
			if($this->Setting->saveSingle($this->data)) {
				$this->set('result', true);
				$this->Session->write('Auth.User.Setting.' . $this->data['Setting']['key'], $this->data['Setting']['value']);
			} else {
				$this->set('result', false);
			}
		}
	}
}
?>