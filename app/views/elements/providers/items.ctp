		<div id="item-wrapper">
			<div id="view-detail">
				<h1 id="view-name-wrapper">
					<span id="view-name">&nbsp;</span>
					<button id="view-delete">Delete</button>
				</h1>
				<div id="view-actions">
					<ul>
						<li>
							Show:
							<div id="view-unread-link-wrapper" class="item-view-toggle">
								<div class="item-view-toggle item-link"><?php echo $html->link('<span class="view-detail-count">0</span> new items', '#', array('class' => 'view-unread', 'escape' => false)) ?></div>
								<div class="item-view-toggle item-text"><span class="view-detail-count">0</span> new items</div>
							</div>
							-
							<div id="view-all-link-wrapper" class="item-view-toggle">
								<div class="item-view-toggle item-link"><?php echo $html->link(__('all items', true), '#', array('class' => 'view-all')) ?></div>
								<div class="item-view-toggle item-text"><?php __('all items') ?></div>
							</div>
						</li>
						<li>
							<div id="view-mark-as-read-wrapper">
								<button id="view-mark-as-read">Mark all as read</button>
								<button id="view-mark-range-as-read">Select an action</button>
							</div>
						</li>
						<li>
							<?php echo $html->link(__('Refresh', true), array('controller' => 'items', 'action' => 'update'),
																			 array('escape' => false, 'id' => 'view-update-single', 'class' => 'provider-update')) ?>
						</li>
						<li>
							<?php
								echo $this->Form->create('Item', array('id' => 'item-hide-forwards-form', 'url' => array('controller' => 'items', 'action' => 'index')));
								echo $this->Form->input('hide retweets', array('type' => 'checkbox'));
								echo $this->Form->end();
							?>
						</li>
					</ul>

					<ul id="view-mark-as-read-expanded" class="ui-corner-bottom">
						<li><?php echo $html->link('All items', array('controller' => 'items', 'action' => 'mark_as_read')) ?></li>
						<li><?php echo $html->link('All older than 6 hours', array('controller' => 'items', 'action' => 'mark_as_read', 'range' => '-6 hours')) ?></li>
						<li><?php echo $html->link('All older than a day', array('controller' => 'items', 'action' => 'mark_as_read', 'range' => '-1 day')) ?></li>
						<li><?php echo $html->link('All older than two days', array('controller' => 'items', 'action' => 'mark_as_read', 'range' => '-2 day')) ?></li>
						<li><?php echo $html->link('All older than a week', array('controller' => 'items', 'action' => 'mark_as_read', 'range' => '-1 week')) ?></li>
					</ul>
								
				</div>
			</div>
			<div id="view-items"></div>
		</div>
		
		<div id="add-provider" title="<?php __('Add a subscription') ?>" class="overlay">
			<div id="tabs">
				<ul>
					<li><a href="#subscribe-account">Account</a></li>
					<li><a href="#subscribe-follow">Ninja Follow</a></li>
					<li><a href="#subscribe-search">Tag</a></li>
				</ul>
				<div id="subscribe-account">
					<?php echo $this->element('providers/account') ?>
				</div>
				<div id="subscribe-follow">
					<?php echo $this->element('providers/follow') ?>
				</div>
				<div id="subscribe-search">
					<?php echo $this->element('providers/search') ?>
				</div>
			</div>
		</div>
	
		<div id="update-status"  title="<?php __('Send a tweet') ?>" class="overlay">
			<?php echo $this->element('providers/update_status') ?>
		</div>
	
		<div id="origin-mute"  title="<?php __('Mute') ?>" class="overlay">
			<?php echo $this->element('origins/mute') ?>
		</div>

		<div id="origin-unfollow"  title="<?php __('Unfollow') ?>" class="overlay">
			<?php echo $this->element('origins/unfollow') ?>
		</div>
		
		<div id="origin-tags"  title="<?php __('Edit Tags') ?>" class="overlay">
			<?php echo $this->element('origins/tags') ?>
		</div>
		
		<div id="provider-track-replies"  title="<?php __('Track Replies') ?>" class="overlay">
			<?php echo $this->element('providers/track-replies') ?>
		</div>
		
		<div id="status" class="ui-corner-bottom">
		</div>