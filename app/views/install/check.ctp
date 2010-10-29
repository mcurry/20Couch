<div class="anony-form">
	<h1><?php __('Step 1: Checking System') ?></h1>
	<table class="checklist">
		<tr><th><?php __('config directory writable') ?></th><td><?php echo $html->image($site->checkOrX($checklist['config_is_writable'])) ?></td></tr>
		<?php if (!$checklist['config_is_writable']) { ?>
			<tr><td colspan="2" class="help">chmod 777 <?php echo CONFIGS ?></td></tr>	
		<?php } ?>
	
		<tr><th><?php __('tmp directory writable') ?></th><td><?php echo $html->image($site->checkOrX($checklist['tmp_is_writable'])) ?></td></tr>
		<?php if (!$checklist['tmp_is_writable']) { ?>
			<tr><td colspan="2" class="help">chmod -R 777 <?php echo TMP ?></td></tr>	
		<?php } ?>

		<tr><th><?php __('cache directory writable') ?></th><td><?php echo $html->image($site->checkOrX($checklist['cache_is_writable'])) ?></td></tr>
		<?php if (!$checklist['cache_is_writable']) { ?>
			<tr><td colspan="2" class="help">chmod -R 777 <?php echo CACHE ?></td></tr>	
		<?php } ?>
		
		<tr><th><?php __('Function json_decode exists') ?></th><td><?php echo $html->image($site->checkOrX($checklist['json_decode_exists'])) ?></td></tr>
		<?php if (!$checklist['json_decode_exists']) { ?>
			<tr><td colspan="2" class="help">The json_decode function wasn't found.  If you are running a PHP version less then 5.2 you need to install the <?php echo $html->link('PECL json package', 'http://pecl.php.net/package/json', array('target' => '_blank')) ?>.  You can try: "pecl install json" from the command line if you're on Linux</td></tr>	
		<?php } ?>
		
		<tr><th><?php __('MySQL Support') ?></th><td><?php echo $html->image($site->checkOrX($checklist['mysql_support'])) ?></td></tr>
		<?php if (!$checklist['mysql_support']) { ?>
			<tr><td colspan="2" class="help">It looks like you don't have mysql setup for PHP.  Check out <?php echo $html->link('the MySQL docs on php.net', 'http://www.php.net/manual/en/mysql.installation.php', array('target' => '_blank')) ?></td></tr>	
		<?php } ?>
	</table>

	<?php
		if(!array_search(false, $checklist)) {
			echo $html->link(__('Continue >>', true), array('controller' => 'install', 'action' => 'db'), array('class' => 'next'));
		} else {
			echo $html->link(__('Recheck', true), array('controller' => 'install', 'action' => 'check'), array('class' => 'next'));
		}
	?>
</div>