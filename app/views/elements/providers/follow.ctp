<p>Ever want to get someone's updates, but not actually follow them?  That is what ninja follow is for.  Plus you'll get all their @replies.  Stalk away!</p>
<?php
	echo $this->Form->create('Provider', array('id' => 'provider-follow', 'class' => 'provider-add-form', 'action' => 'add_follow'));
	echo $this->Form->input('service_id', array('type' => 'hidden', 'value' => 3));
	echo $this->Form->input('keyword', array('label' => __('Twitter Name @', true)));
	echo $this->Form->end('Submit');
?>