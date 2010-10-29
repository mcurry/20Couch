<div class="Providers index">
<?php if (empty($providers)) { ?>
	<p class="empty">You have not added any subscriptions.</p>
<?php } else { ?>
	<table cellpadding="0" cellspacing="0">
	<tr>
		<th><?php echo $paginator->sort('name');?></th>
		<th><?php echo $paginator->sort('type');?></th>
		<th><?php echo $paginator->sort(__('Update Every', true), 'update_frequency');?></th>
		<th><?php echo $paginator->sort('created');?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	foreach ($providers as $i => $provider):
		$class = null;
		if ($i % 2 == 1) {
			$class = ' class="altrow"';
		}
	?>
		<tr<?php echo $class;?>>
			<td>
				<?php echo $provider['Provider']['name']; ?>
			</td>
			<td>
				<?php __(Configure::read('Services.' . $provider['Provider']['service_id'])); ?>
			</td>
			<td>
				<?php echo Configure::read('UpdateFrequencies.' . $provider['Provider']['update_frequency']); ?>
			</td>
			<td>
				<?php echo $time->niceShort($provider['Provider']['created']); ?>
			</td>
			<td class="actions">
				<?php echo $html->link(__('Edit', true), array('action' => 'edit', $provider['Provider']['id'])); ?>
				<?php echo $html->link(__('Delete', true), array('action' => 'delete', $provider['Provider']['id']), null, sprintf(__('Are you sure you want to delete %s?', true), $provider['Provider']['name'])); ?>
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