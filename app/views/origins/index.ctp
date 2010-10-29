<div class="Origins index">
<?php if (empty($origins)) { ?>
	<p class="empty"><?php __('You have not muted anyone.') ?></p>
<?php } else { ?>
	<table cellpadding="0" cellspacing="0">
	<tr>
		<th><?php echo $paginator->sort('name');?></th>
		<th><?php echo $paginator->sort('muted_until');?></th>
		<th class="actions"><?php __('Actions');?></th>
	</tr>
	<?php
	foreach ($origins as $i => $origin):
		$class = null;
		if ($i % 2 == 1) {
			$class = ' class="altrow"';
		}
	?>
		<tr<?php echo $class;?>>
			<td>
				<?php echo $html->image($origin['Origin']['avatar']); ?><?php echo $origin['Origin']['name']; ?>
			</td>
			<td>
				<?php
					if($origin['Origin']['muted'] == -1) {
						__('Forever');
					} else {
						echo $time->niceShort($origin['Origin']['muted_until']);
					}
				?>
			</td>
			<td class="actions">
				<?php echo $html->link(__('Unmute', true), array('action' => 'unmute', $origin['Origin']['id'])); ?>
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