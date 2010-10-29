<div class="Plugins index">
<?php if (empty($pluginsList)) { ?>
	<p class="empty">You have not added any plugins.  Upload plugins to <?php echo Configure::read('Plugin.path') ?></p>
<?php } else { ?>
	<table cellpadding="0" cellspacing="0">
	<tr>
		<th><?php echo $paginator->sort('name');?></th>
		<th><?php echo $paginator->sort('active');?></th>
		<th><?php echo $paginator->sort('version');?></th>
		<th><?php echo $paginator->sort('description');?></th>
		<th><?php echo $paginator->sort('created');?></th>
		<th><?php echo $paginator->sort('modified');?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	foreach ($pluginsList as $i => $plugin):
		$class = null;
		if ($i % 2 == 1) {
			$class = ' class="altrow"';
		}
	?>
		<tr<?php echo $class;?>>
			<td>
				<?php
					$out = $plugin['Plugin']['name'];
				
					if(!empty($plugin['Plugin']['author'])) {
						$out .= ' (' . $plugin['Plugin']['author'] . ')';
					}
					
					if(!empty($plugin['Plugin']['link'])) {
						$out = $html->link($out, 'http://' . $plugin['Plugin']['link'], array('target' => '_blank'));
					}
					
					echo $out;
				?>
			</td>
			<td>
				<?php
					if($plugin['Plugin']['active']) {
						echo $html->link($html->image($site->checkOrX($plugin['Plugin']['active'])),
														 array('action' => 'deactivate', $plugin['Plugin']['id']),
														 array('escape' => false));
					} else {
						echo $html->link($html->image($site->checkOrX($plugin['Plugin']['active'])),
														 array('action' => 'activate', $plugin['Plugin']['id']),
														 array('escape' => false));
					}	
				?>
			</td>
			<td>
				<?php echo $plugin['Plugin']['version']; ?>
			</td>
			<td class="left">
				<?php echo $plugin['Plugin']['description']; ?>
			</td>
			<td>
				<?php echo $time->niceShort($plugin['Plugin']['created']); ?>
			</td>
			<td>
				<?php echo $time->niceShort($plugin['Plugin']['modified']); ?>
			</td>
			<td class="actions">
				<?php
					if($plugin['Plugin']['active']) {
						echo $html->link(__('Deactivate', true), array('action' => 'deactivate', $plugin['Plugin']['id']));
					} else {
						echo $html->link(__('Activate', true), array('action' => 'activate', $plugin['Plugin']['id']));
					}	
				?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
	<div class="paging">
		<?php echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
	 | 	<?php echo $paginator->numbers();?>
		<?php echo $paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
	</div>
<?php } ?>
</div>