<?php
/***************************************************************************
 20Couch

 Copyright (c) 2009-2010 Matt Curry

 @author      Matt Curry <matt@pseudocoder.com>
 @license     MIT

 More info at: http://www.20couch.com
****************************************************************************/
?>
<?php
class InstallController extends Controller {
	var $name = 'Install';
	var $layout = 'anony';
	
	var $helpers = array('Session', 'Html', 'Form', 'Js', 'Site');
	var $uses = null;

	function beforeFilter() {
		$File = new File(TMP . 'install');
		if (!$File->exists()) {
			echo $this->render('already_installed');
			die;
		}
	
		$this->set('mobile', false);
		$this->set('plugins', array());
	}

	function index() {

	}

	function check() {
		$checklist = array(
										 'config_is_writable' => is_writable(CONFIGS),
										 'tmp_is_writable' => is_writable(TMP),
										 'cache_is_writable' => is_writable(CACHE),
										 'json_decode_exists' => function_exists('json_decode'),
										 'mysql_support' => function_exists('mysql_connect')
								 );
		$this->set('checklist', $checklist);
	}

	function db() {
		if ($this->data) {
			//test the connection
			App::import('Core', 'ConnectionManager');
			$db = ConnectionManager::create('testDb', $this->data['Install']);

			if ($db->connected) {
				//not supported ATM
				$this->data['Install']['prefix'] = '';
				
				//save the datbase config
				copy(CONFIGS . 'database.php.default', CONFIGS . 'database.php');
				$File = new File(CONFIGS . 'database.php');
				$config = $File->read();
				$this->data['Install']['cipher_key'] = sha1(time() . rand(0, 9999999));
				foreach($this->data['Install'] as $key => $val) {
					$config = str_replace('%%' . strtoupper($key) . '%%', $val, $config);
				}

				$File->write($config);

				//build the tables
				$File = new File(CONFIGS . 'sql' . DS . '20couch.sql');
				$tables = explode(';', $File->read());
				foreach($tables as $table) {
					$table = trim($table);
					if (empty($table)) {
						continue;
					}
					$db->query(trim(str_replace("%%PREFIX%%", $this->data['Install']['prefix'], $table)));
				}

				//delete the install file and redirect
				$File = new File(TMP . 'install');
				$File->delete();
				$this->redirect(array('controller' => 'users', 'action' => 'install'));
			} else {
				$this->Session->setFlash('Couldn\'t connect to database.  Please check settings.');
			}
		}
	}
}
?>