<?php
	$classes = array('item', 'origin-' . $item['Origin']['id']);
	
	if(!empty($item['Item']['reply_user_provider_key']) && !empty($providersKeys) &&
		 in_array($item['Item']['reply_user_provider_key'], $providersKeys)) {
		$classes[] = 'mention';
	}

	if(!empty($item['Item']['message'])) {
		$classes[] = 'dmessage';
	}
	
	if(!empty($item['ForwardOrigin']['id']) || $site->isForward($item['Item']['text'])) {
		$classes[] = 'forwarded';
		
		if(!empty($item['ForwardOrigin']['id'])) {
			$classes[] = 'api-forwarded';
		}
	}
	
	$itemId = 'item-' . $item['Item']['id'];
?>

<div id="<?php echo $itemId ?>" class="<?php echo implode(' ', $classes) ?>">
	<div class="avatar-wrapper">
		<?php
			if($mobile) {
				echo '<a href="/items/actions/' . $item['Item']['id'] . '" data-rel="dialog">';
			}
			if(!empty($item['ForwardOrigin']['id'])) {
				echo $html->image($item['ForwardOrigin']['avatar'], array('class' => 'avatar'));
				echo $html->image($item['Origin']['avatar'], array('class' => 'sub-avatar'));
			} else {
				echo $html->image($item['Origin']['avatar'], array('class' => 'avatar'));
			}
			if($mobile) {
				echo '</a>';
			}
		?>
	</div>
	<div class="item-wrapper">
		<div class="item-info">
			<div class="item-meta">
				<?php
					if(!empty($item['ForwardOrigin']['id'])) {
						echo $html->link($item['ForwardOrigin']['name'], $item['ForwardOrigin']['link']) . " retweeted by ";
					}
					echo $html->link($item['Origin']['name'], $item['Origin']['link'])
				?>
				(<?php
					echo $html->link($site->timeAgoInWords($item['Item']['posted']), $item['Item']['link'], array('class' => 'time-ago'));
					
					if(!empty($item['Item']['client'])) {
						echo ' from ' . html_entity_decode($item['Item']['client']);
					}

					if($view['id'] == 'all') {
						echo ', ' . $item['Provider']['name']; 
					}
				?>)
				 
				<?php
					if(!$mobile) {
						echo $html->link('expand', '#', array('class' => 'actions-control'));
					}
				?>
			</div>
			
			<?php
				if (!$mobile) {
					echo $this->element('items/actions', compact('item', 'subItems'));
				}
			?>
		</div>
	
		<div class="text"><?php echo $site->enhanceText($item['Item']['text']) ?></div>
		
		<?php echo $this->element('items/sub_item', compact('item', 'depth')); ?>
		
		<?php
			$data = array('Origin' => array('id' => $item['Origin']['id'],
																			'name' => $item['Origin']['name']),
										'Item' => array('id' => $item['Item']['id'],
																		'service_id' => $item['Provider']['service_id'],
																		'provider_id' => $item['Item']['provider_id'],
																		'text' => $item['Item']['text'],
																		'message' => $item['Item']['message'],
																		'posted' => strtotime($item['Item']['posted'])));
		?>
		<script type="text/javascript">
			$(function() {
				$("#<?php echo $itemId ?>").data("meta", <?php echo $js->object($data) ?>);
			});
		</script>
	</div>
</div>
