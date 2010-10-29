<div id="menu">
	<div id="logo">
		<?php echo $html->link('20Couch', 'http://20couch.com') ?>
	</div>
	<ul>
		<li><?php
			echo $this->Form->create('Item', array('id' => 'search', 'url' => array('controller' => 'items', 'action' => 'search')));
			echo $this->Form->input('search', array('label' => false));
			echo $this->Form->end(__('Search', true));
		?></li>
		<li>|</li>
		<li><?php echo $html->link(__('Settings', true), array('controller' => 'settings', 'action' => 'index')) ?></li>
		<li>|</li>
		<!--
		<li><?php echo $html->link(__('Help', true), Configure::read('20Couch.home') . '/help') ?></li>
		<li>|</li>
		<li><?php echo $html->link(__('About', true), Configure::read('20Couch.home') . '/about') ?></li>
		<li>|</li>
		-->
		<li><?php echo $html->link(__('Bugs', true), 'http://20couch.lighthouseapp.com/projects/46779-20couch/tickets/bins/114582') ?></li>
		<li>|</li>
		<li><?php echo $html->link('v' . Configure::read('Version'), array('controller' => 'update', 'action' => 'index')) ?></li>
		<?php if(User::get('Setting.show_remaining_requests')) { ?>
			<li>|</li>
			<li><span id="twitter-api-requests-remaining"><?php echo Configure::read('Twitter.apiRequestsRemaining') ?></span> remaining</li>
		<?php } ?>
		<li>|</li>
		<li><?php echo $html->link(__('Sign out', true), array('controller' => 'users', 'action' => 'logout')) ?></li>
	</ul>
</div>