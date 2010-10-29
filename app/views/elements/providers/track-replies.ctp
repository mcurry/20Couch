<?php
	echo $this->Form->create('Provider', array('id' => 'provider-track-replies-form', 'url' => array('controller' => 'providers', 'action' => 'add_track_reply')));
	echo $this->Form->input('service_id', array('type' => 'hidden', 'value' => 4));
	echo $this->Form->input('item_id', array('type' => 'hidden', 'id' => 'ProviderTrackReplyId'));
	echo '<p>Tracking replies can chew through your API requests pretty quickly.  Make sure not to track too many at once and you should probably avoiding tracking replies to super-celebs.  You\'ve been warned.</p>';
	echo $this->Form->end('Let\'s do it!');
?>