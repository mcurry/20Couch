<?php
	echo $this->Form->create('Origin', array('id' => 'origin-mute-form', 'url' => array('controller' => 'origins', 'action' => 'mute')));
	echo $this->Form->input('id', array('type' => 'hidden', 'id' => 'OriginMuteId'));
	echo $this->Form->input('muted_length', array('label' => __('Mute for', true), 'options' => array(1 => '1 hour',
																																															24 => '1 day',
																																															-1 => 'Forever')));
	echo $this->Form->end('Submit');
?>