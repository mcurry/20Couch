<?php
	$class = '';
	if($mobile) {
		$class = ' class="mobile"';
	}
?>
<div data-role="page"<?php echo $class ?>>
<?php echo $content_for_layout; ?>
</div>