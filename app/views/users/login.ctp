<div class="anony-form">
	<div data-role="header" role="banner">
		<h1 role="heading"><?php __('20Couch Login') ?></h1>
	</div>
	<?php echo $session->flash(); ?>
	<?php
			echo $this->Session->flash('auth');
			echo $this->Form->create('User', array('action' => 'login'));
			echo $this->AppForm->input('username');
			echo $this->AppForm->input('password');
			echo $this->AppForm->input('remember', array('type' => 'checkbox'));
			echo $this->Form->end('Login');
	?>
</div>