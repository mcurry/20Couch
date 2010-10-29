<?php
	echo $this->Form->create('Provider', array('class' => 'settings-form', 'url' => array('controller' => 'providers', 'action' => 'edit')));
	echo $this->Form->input('id');
	echo $this->Form->input('name');
	echo $this->Form->input('update_frequency', array('type' => 'select',
																							'label' => __('Update every', true),
																							'options' => Configure::read('UpdateFrequencies')
																							));
	echo $this->Form->end(__('Submit', true));
?>
