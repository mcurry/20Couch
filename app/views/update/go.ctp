<div id="update">
	<?php $html->script($site->staticVersion('jquery.update'), false); ?>
	
	<p>Downloading update... <span id="downloadStatus">waiting</span></p>
	<p>Extracting update... <span id="extractStatus">waiting</span></p>
	<p>Processing update... <span id="processStatus">waiting</span></p>
	
	<p><?php echo $html->link('Check out what\'s changed >>', array('controller' => 'update', 'action' => 'notes'), array('id' => 'update-done')); ?></p>
	
	<script type="text/javascript">
		$(function() {
			$.Update.go();
		});
	</script>
</div>