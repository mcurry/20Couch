<?php
if($items) {
	foreach($items as $item) {
		echo $this->element('items/item', array('item' => $item));
	}
}
?>