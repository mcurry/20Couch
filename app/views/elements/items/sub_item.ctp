<?php
	$subItems = $this->Site->getSubItems($item, $items, $replies, $mobile, $depth);	
	
	if($subItems && (!$mobile || ($mobile && $depth <= 1))) {
		foreach($subItems as $class => $subItem) {
			foreach($subItem as $sItem) {
			?>
				<div class="<?php echo $class ?>"><?php echo $this->element('items/item', array('item' => $sItem, 'depth' => $depth + 1)) ?></div>
			<?php
			}
		}
	}
?>