<div class="anony-form">
	<h1><?php __('Step 2: Setup Database') ?></h1>
	<?php echo $session->flash(); ?>

	<?php
			echo $this->Form->create('Install', array('url' => array('controller' => 'install', 'action' => 'db')));
			echo $this->Form->input('driver', array('type' => 'hidden', 'value' => 'mysql'));
			echo $this->Form->input('host');
			echo $this->Form->input('database');
			echo $this->Form->input('login');
			echo $this->Form->input('password');
			//echo $this->Form->input('prefix');
			echo $this->Form->end('Submit');
	?>
</div>