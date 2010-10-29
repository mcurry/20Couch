<div class="anony-form">
	<h1><?php __('Step 0: Thanks') ?></h1>
	<p>Thanks for checking out 20Couch.  You've made an awesome decision.</p>
	<h1 class="pirate">A special message to pirates</h1>
	<p>Ahoy ye matey.  Ye plundered my booty like it was a fair lass.  It be recommended that <?= $html->link('ye send some doubloons', 'http://20couch.com/get-it', array('target' => '_blank')) ?> before I make ye walk the plank.  <strong>ARRRRRR!!!</strong>
	<?php echo $html->link(__('Continue >>', true), array('controller' => 'install', 'action' => 'check'), array('class' => 'next')); ?>
</div>