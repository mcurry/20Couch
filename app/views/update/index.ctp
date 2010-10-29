<p>Your version: <?php echo $yourVersion ?></p>
<p>Latest version: <?php echo $latestVersion ?></p>

<?php if ($yourVersion < $latestVersion) { ?>
	<p>
		<?php
			if($registrationKey) {
				echo $html->link('Manual Update', Configure::read('20Couch.home') . '/update/' . $registrationKey, array('target' => '_blank'));
			} else {
				echo $html->link('Manual Update', Configure::read('20Couch.home') . '/update/', array('target' => '_blank'));
			}
		?>
	</p>
	<?php if(array_search(false, $checklist) !== false) { ?>
		<p>Auto update is currently unavailble.  See below to fix:</p>
		<div id="auto-update-checklist">
			<table class="checklist">
				<tr><th><?php __('root directory writable') ?></th><td><?php echo $html->image($site->checkOrX($checklist['root_is_writable'])) ?></td></tr>
				<?php if (!$checklist['root_is_writable']) { ?>
					<tr><td colspan="2" class="help">chmod -R a+w <?php echo ROOT ?></td></tr>	
				<?php } ?>
			
				<tr><th><?php __('registration key is set') ?></th><td><?php echo $html->image($site->checkOrX($checklist['registration_key_is_set'])) ?></td></tr>
				<?php if (!$checklist['registration_key_is_set']) { ?>
					<tr><td colspan="2" class="help"><?php echo $html->link('Go to the Settings tab and enter your registration key',
																																	array('controller' => 'settings', 'action' => 'index')) ?></td></tr>	
				<?php } ?>
				
				<tr><th><?php __('unzip method available') ?></th><td><?php echo $html->image($site->checkOrX($checklist['unzip_method_available'])) ?></td></tr>
				<?php if (!$checklist['unzip_method_available']) { ?>
					<tr><td colspan="2" class="help">To unzip the archive you need to have <?php echo $html->link('PHP\'s Zip installed',
																																	'http://www.php.net/manual/en/book.zip.php', array('target' => '_blank')) ?>
																																	or a <?php echo $html->link('command line version of unzip', 'http://www.info-zip.org', array('target' => '_blank')) ?>
																																	with the correct path set.</td></tr>	
				<?php } ?>
			</table>
		</div>
	<?php } else { ?>
		<p><?php echo $html->link('Auto Update', array('controller' => 'update', 'action' => 'go')); ?></p>
	<?php } ?>
<?php } ?>