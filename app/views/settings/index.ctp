<?php
	echo $this->Form->create('Setting', array('class' => 'settings-form', 'url' => array('controller' => 'settings', 'action' => 'index')));
	foreach($settings as $setting) {
		echo $this->Form->input($setting['Setting']['key'], array('value' => $setting['Setting']['value']));
		
		$helpKey = $setting['Setting']['key'] . "_help";
		$help = __($helpKey, true);
		if($help != $helpKey) {
			echo '<div class="help">' . $help . '</div>';
		}
	}
	echo $this->Form->end(__('Submit', true));
?>