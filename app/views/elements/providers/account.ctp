<p>20Couch uses <?php echo $html->link('Oauth', 'http://blog.twitter.com/2009/04/whats-deal-with-oauth.html') ?> to log into your Twitter account.  This keeps your password safe, so while all your friends are getting their accounts hacked you can laugh and point.  You're welcome.</p>
<p>To setup your account <?php	echo $html->link(__('click this link and sign in at twitter', true), array('controller' => 'providers', 'action' => 'titter_oauth_redirect'), array('target' => '_blank')); ?>.  You'll be given a 7 digit pin, which you enter below.</p>
	
<?php
	echo $this->Form->create('Provider', array('id' => 'provider-account', 'class' => 'provider-add-form', 'action' => 'add'));
	echo $this->Form->input('service_id', array('type' => 'hidden', 'value' => 1));
	echo $this->Form->input('pin');
	echo $this->Form->end('Submit');
?>