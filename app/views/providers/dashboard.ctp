<div id="wrapper">
	<div id="nav">
		<div id="actions">
			<ul data-role="listview" data-inset="true">
				<li><?php echo $html->link(__('Send a tweet', true), array('action' => 'update_status'),
																		 array('escape' => false, 'id' => 'update-status-link')) ?></li>
				<li><?php echo $html->link(__('Refresh', true), array('controller' => 'items', 'action' => 'update', 'all'),
																		 array('escape' => false, 'id' => 'provider-update-all')) ?></li>				
				<?php if (!$mobile) { ?>
					<li><?php echo $html->link(__('Add a subscription', true), array('action' => 'add'),
																			 array('escape' => false, 'id' => 'add-provider-link')) ?></li>
				<?php } ?>	
			</ul>
		</div>
		
		<div id="sources">
			<div id="general">
				<ul data-role="listview" data-inset="true">
					<li><?php
						$count = 0;
						if(!empty($providers['all']['item_count'])) {
							$count = $providers['all']['item_count'];
						}
						
						echo $html->link(__('All items', true) . ' <span class="item-count ui-li-count">' . $count . '</span>',
																		 array('controller' => 'items', 'action' => 'index', 'all'),
																		 array('id' => 'provider-all', 'escape' => false)); ?></li>
				</ul>
			</div>
			
			<div id="providers">
				<?php echo $this->element('providers/list', array('type' => 'TwitterAccount', 'providers' => $providers)); ?>

				<?php echo $this->element('providers/list', array('type' => 'TwitterSearch', 'providers' => $providers)); ?>

				<?php echo $this->element('providers/list', array('type' => 'TwitterFollow', 'providers' => $providers)); ?>
				
				<?php echo $this->element('providers/list', array('type' => 'TwitterReply', 'providers' => $providers)); ?>
			</div>
			
			<div id="tags">
				<ul data-role="listview" data-inset="true">
					<?php foreach($tags as $tag) { ?>
						<li><?php echo $html->link($tag['Tag']['name'] . ' (<span class="item-count">' . $site->getItemCount($tag['Tag']['id'], $tags, 'Tag') . '</span>)',
																			 array('controller' => 'items', 'action' => 'index', 'tag_id' => $tag['Tag']['id']),
																			 array('id' => 'tag-' . $tag['Tag']['id'], 'escape' => false)); ?></li>
					<?php } ?>
				</ul>
			</div>
		</div>
		
		<?php
			if ($mobile) {
				echo $this->element('providers/mobile_extras');
			}
		?>
	</div>
	
	<?php
		if (!$mobile) {
			echo $this->element('providers/items');
		}
	?>
</div>
<?php if(!$mobile) { ?>
	<script type="text/javascript">
		$(function(){
			<?php if (empty($providers)) { ?>
				$("#add-provider-link").click();
			<?php } ?>
		});
	</script>
<?php } ?>