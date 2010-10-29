<?php
class AppError extends ErrorHandler {
	function error404($params) {
		$this->controller->layout = "anony";
		
		$installed = true;
		$File = new File(TMP . 'install');
		if($File->exists()) {
			$installed = false;
		}
		
		if(!$installed) {
			$this->_outputMessage('install');
		} else {
			parent::error404($params);
		}
	}
}
?>