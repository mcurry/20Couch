<?php
$class = array('service-wrapper');
if(empty($providers[$type])) {
	$class[] = 'empty';
}
?>
<div class="<?php echo implode(' ', $class) ?>">
	
	<ul class="service-<?php echo Configure::read("ServicesName." . $type) ?>" data-role="listview" data-inset="true">
		<?php if(!empty($providers[$type])) { ?>
			<li data-role="list-divider" role="heading"><h4><?php echo Inflector::pluralize(__($type, true)) ?></h4></li>
			<?php foreach($providers[$type] as $provider) { ?>
				<li><?php echo $html->link($provider['Provider']['name'] . ' <span class="item-count ui-li-count">' . $provider['Provider']['item_count'] . '</span>',
																	 array('controller' => 'items', 'action' => 'index', 'provider_id' => $provider['Provider']['id']),
																	 array('id' => 'provider-' . $provider['Provider']['id'], 'data-transition' => 'slide', 'escape' => false)); ?></li>
			<?php } ?>
		<?php } ?>
	</ul>
</div>