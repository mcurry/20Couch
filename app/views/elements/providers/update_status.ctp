<?php
	if(empty($statusOptions)) {
		$statusOptions = array();
	}
	
	echo $this->Form->create('Provider', array('id' => 'update-status-form', 'url' => array('controller' => 'providers', 'action' => 'update_status')));
	echo $this->Form->input('action', array('type' => 'hidden'));
	echo $this->Form->input('item_id', array('type' => 'hidden'));
	echo $this->AppForm->input('provider_id', array('label' => false, 'options' => $updatable));
	echo $this->AppForm->input('status', array_merge($statusOptions, array('type' => 'textarea', 'label' => false)));
?>
	<div id="chars-remaining-wrapper"><span id="chars-remaining">140</span> <?php __('characters remaining') ?></div>
<?php
	echo $this->Form->end('Submit');
?>