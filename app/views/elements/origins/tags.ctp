<?php
	echo $this->Form->create('Origin', array('id' => 'origin-tags-form', 'url' => array('controller' => 'origins', 'action' => 'tags')));
	echo $this->Form->input('id', array('type' => 'hidden', 'id' => 'TagOriginId'));
	echo $this->Form->input('tags_text');
	echo $this->Form->end('Submit');
?>