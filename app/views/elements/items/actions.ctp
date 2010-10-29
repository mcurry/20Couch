<div class="actions" data-role="controlgroup">
		<?php if(false && !$subItems && !empty($item['Item']['reply_status_provider_key'])) { ?>
			<?php echo $html->link(__('View conversation', true),
														 array('controller' => 'items', 'action' => 'conversation', $item['Item']['id']),
														 array('class' => 'conversation', 'data-role' => 'button')); ?>
		<?php } ?>
		<?php echo $html->link(__('Reply', true),
													 array('controller' => 'providers', 'action' => 'reply', $item['Item']['id']),
													 array('class' => 'reply', 'data-role' => 'button')) ?>
		<?php echo $html->link(__('Retweet', true),
													 array('controller' => 'providers', 'action' => 'forward', $item['Item']['id']),
													 array('class' => 'forward', 'data-role' => 'button')) ?>
		<?php echo $html->link(__('Mute', true),
													 array('controller' => 'origins', 'action' => 'mute', $item['Origin']['id']),
													 array('class' => 'mute', 'data-role' => 'button')) ?>
		
		<?php if($view['service_id'] != Configure::read('ServicesName.TwitterReply')) { ?>
			<?php echo $html->link(__('Replies', true),
														 array('controller' => 'providers', 'action' => 'add_track_replies', $item['Item']['id']),
														 array('class' => 'track-replies', 'data-role' => 'button')) ?>
		<?php } ?>
	
		<?php if(!empty($item['Origin']['following'])) { ?>
				<?php echo $html->link(__('Unfollow', true),
															 array('controller' => 'origins', 'action' => 'unfollow', $item['Origin']['id']),
															 array('class' => 'unfollow', 'data-role' => 'button')) ?>
		<?php } ?>
</div>