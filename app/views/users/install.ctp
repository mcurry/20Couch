<div class="anony-form">
	<h1><?php __('Step 3: Create User');?></h1>
	<p>Create a user for accessing 20Couch.  This is <strong>not your Twitter</strong> account.
	<?php
		echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' => 'install')));
		echo $this->Form->input('username');
		echo $this->Form->input('password', array('value' => ''));
		echo $this->Form->input('verify_password', array('type' => 'password', 'value' => ''));
		echo $this->Form->end('Submit');
	?>
</div>