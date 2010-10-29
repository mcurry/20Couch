<?php
class UpdateShell extends Shell {
	var $uses = array('Provider');

	function main() {
		$demo = Configure::read('demo');
		Configure::write('demo', false);

		$this->Provider->update('all');
		$this->out(implode("\n", $this->Provider->msg));
		
		if($demo) {
			$this->Provider->Item->markAsRead(array('use_posted' => true, 'type' => 'provider', 'timestamp' => date('Y-m-d H:i:s', strtotime('-1 day'))));
		}
	}

}
?>