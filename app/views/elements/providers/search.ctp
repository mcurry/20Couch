<p>Follow your favorite #hashtags so that you never miss another #lolcat.</p>
<?php
	echo $this->Form->create('Provider', array('id' => 'provider-search', 'class' => 'provider-add-form', 'action' => 'add_search'));
	echo $this->Form->input('service_id', array('type' => 'hidden', 'value' => 2));
	echo $this->Form->input('keyword');
	echo $this->Form->end('Submit');
?>