<?php
$timestamp = date('Y-m-d H:i:s');
$unixTimestamp = time();

if(empty($auto)) {
	$auto = false;
}
if($items) {
	if($mobile) {
		$backBtn = $this->Html->link('&laquo; Home', array('controller' => 'providers', 'action' => 'dashboard'),
																 array('escape' => false));
		
		$itemIds = implode(',', Set::extract('/Item/id', $items));
		
		echo $this->Form->create('Item', array('action' => 'mark_as_read_by_id'));
		echo $this->Form->input('id', array('type' => 'hidden', 'value' => $itemIds));
		echo $this->Form->input('provider_id', array('type' => 'hidden', 'value' => $view['id']));
		echo $this->Form->input('redirect', array('type' => 'hidden', 'value' => $this->here));
		
		$markAsReadForm = $this->Form->submit(__('Mark these as read', true), array('div' => array('class' => 'ui-btn-right')));
	}
?>
	<div id="item-index-<?php echo $unixTimestamp ?>" class="item-index">
<?php
	if($mobile) {
		echo '<div data-role="header" data-position="inline">';
		echo $backBtn;
		echo '<h1>&nbsp;</h1>';
		echo $markAsReadForm;
		echo '</div>';
	}
	
	$display = true;
	if($view['paginating'] && $auto) {
		$display = false;
	}
	
	if($this->action == 'update' && $view['mode'] != 'default') {
		$display = false;
	}

	if($display) {
		$depth = 0;
		foreach($items as $item) {
			if(isset($providerKeys)) {
				if(!in_array($item['Item']['reply_status_provider_key'], $providerKeys)) {
					echo $this->element('items/item', compact('item', 'depth'));
				}
			} else {
				echo $this->element('items/item', compact('item', 'depth'));
			}
		}
	}

	if($mobile) {
		echo '<div data-role="header" data-position="inline">';
		echo $backBtn;
		echo '<h1>&nbsp;</h1>';
		echo $markAsReadForm;
		echo '</div>';
		echo $this->Form->end();
	}
?>
	</div>
<?php
} else if(empty($auto)) {
?>
	<div class="item no-items">
	<h2><?php __('You have no unread items') ?></h2>
	<p><?php echo $html->link(__('View all items', true), array_merge($this->passedArgs, array('view' => 'all')), array('class' => 'view-all')) ?></p>
	</div>
<?php
}
?>

<script type="text/javascript">
	<?php if(!$mobile) {
		$view['hasNextPage'] = $paginator->hasNext();
	?>
			$(function(){
				$("#item-index-<?php echo $unixTimestamp ?> .actions-control").button({text: false, icons: { primary: 'ui-icon-plus' }});
			
				if($.Couch.twitterApiRequestsRemaining.html() == "?") {
					$.Couch.twitterApiRequestsRemaining.html("<?php echo Configure::read('Twitter.apiRequestsRemaining') ?>");
				}
				
				<?php if(!empty($auto) && $items) { ?>
					$.Couch.viewItems.find(".no-items").remove();	
				<?php } ?>
	
				<?php if(!empty($search)) { ?>
					$.Couch.viewActions.hide();
					$.Couch.viewName.addClass("search");
					$("#sources a").removeClass("selected");
					$.Couch.namedView = 'provider_id:search';
				<?php } ?>
				
				$.Couch.clearStatus();
				$.Couch.updateSelectedProvider(<?php echo $js->object(compact('auto', 'view', 'timestamp', 'flash', 'counts')) ?>);
			});
	<?php } ?>
</script>