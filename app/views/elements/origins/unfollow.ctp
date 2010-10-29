<p>Are you sure you want to unfollow <span id="unfollow-name"></span>?</p>
<?php
	echo $this->Form->create('Origin', array('id' => 'origin-unfollow-form', 'url' => array('controller' => 'origins', 'action' => 'unfollow')));
	echo $this->Form->input('id', array('type' => 'hidden', 'id' => 'OriginUnfollowId'));
	echo $this->Form->input('following', array('type' => 'hidden', 'value' => false));
	echo $this->Form->end('Yes!');
?>