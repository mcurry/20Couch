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
class UpdateController extends AppController {
	var $name = 'Update';
	var $layout = 'settings';
	var $uses = null;
	
	function beforeFilter() {
		parent::beforeFilter();
		
		if($this->action != 'index' && Configure::read('demo')) {
			$this->redirect('index');
		}
	}

	function index() {
		$this->helpers[] = 'Site';
		
		$Setting = ClassRegistry::init('Setting');
		$yourVersion = $Setting->find('value', 'version');

		//get the current version
		App::import('Core', 'HttpSocket');
		$Http = new HttpSocket();
		$latestVersion = trim($Http->get(Configure::read('20Couch.home') . '/latest'));
	
		$this->set(compact('yourVersion', 'latestVersion'));
		
		if($yourVersion < $latestVersion) {
			$registrationKey = $Setting->find('value', 'registration_key');
			
			//check if auto update is available
			$Setting = ClassRegistry::init('Setting');
			$checklist = array(
											 'root_is_writable' => is_writable(ROOT),
											 'registration_key_is_set' => $registrationKey,
											 'unzip_method_available' => $this->__checkUnzip()
									 );
			$this->set(compact('checklist', 'registrationKey'));
		}
	}

	function go() {

	}
	
	function notes() {
		
	}

	function download() {
		$result = array('success' => true,
										'error' => null);

		$home = Configure::read('20Couch.home');
		App::import('Core', array('HttpSocket', 'File'));
		$Http = new HttpSocket();

		$Setting = ClassRegistry::init('Setting');
		$url = sprintf('%s/registrations/direct/%s/' . Configure::read('Update.file'), $home, $Setting->find('value', 'registration_key'));
		$latest = $Http->get($url);
		if ($latest === false || $Http->response['status']['code'] != 200) {
			if($Http->response['status']['code'] == 401) {
				$msg = 'Invalid registration key';
			} else {
				$msg = 'Unable to retrieve latest file from ' . $home;
			}
			
			$this->log($url);
			$this->log($Http->response);
			$result = array('success' => false,
											'error' => $msg);
			$this->set('result', $result);
			return;
		}

		$File = new File(TMP . Configure::read('Update.file'), false);
		$File->write($latest);
		$File->close();

		$latestChecksum = trim($Http->get($home . '/checksum'));
		$yourChecksum = sha1_file(TMP . Configure::read('Update.file'));

		if ($yourChecksum != $latestChecksum) {
			$result = array('success' => false,
											'error' => 'Checksum doesn\'t match (' . $yourChecksum . ' vs ' . $latestChecksum . ')');
			$this->set('result', $result);
			return;
		}

		$result = array('success' => true,
										'error' => null);
		$this->set('result', $result);
	}

	function extract() {
		$result = false;
		$msg = '';

		if (class_exists('ZipArchive')) {
			$msg = '(using ZipArchive)';
			$zip = new ZipArchive;
			$zip->open(TMP . Configure::read('Update.file'));
			$result = $zip->extractTo(ROOT);
		} else {
			$cmd = sprintf('unzip -o ' . TMP . Configure::read('Update.file') . ' -d' . ROOT);
			$msg = '(using ' . $cmd . ')';
			exec($cmd, $output, $return);
			if ($return === 0) {
				$result = true;
			} else {
				$this->log($return);
				$this->log($output);
			}
		}

		if ($result) {
			$File = new File(TMP . 'install');
			$File->delete();

			$result = array('success' => true,
											'error' => null);
		} else {
			$result = array('success' => false,
											'error' => 'Error extracting zip ' . $msg);
		}


		$this->set('result', $result);
	}

	function process() {
		$Setting = ClassRegistry::init('Setting');
		$version = $Setting->find('value', 'version');

		$Folder = new Folder(CONFIGS . 'sql' . DS . 'updates');
		$files = $Folder->read();

		$error = '';		
		if (!empty($files[1])) {
			foreach($files[1] as $file) {
				$fileVersion = str_replace('.sql', '', $file);

				if ($fileVersion > $version) {
					$File = new File(CONFIGS . 'sql' . DS . 'updates' . DS . $file);
					$sqls = explode(';', $File->read());
					foreach($sqls as $sql) {
						$sql = trim($sql);
						if (empty($sql)) {
							continue;
						}

						$result = $Setting->query($sql);
						if ($result === false) {
							$db = $Setting->getDatasource();
							$error = sprintf('Error running %s - %s (%s)', $file, $db->lastError(), $sql);
							break 2;
						}
					}
				}
			}
		}

		if ($error) {
			$result = array('success' => false,
											'error' => $error);
			$this->set('result', $result);
			return;
		}

		//clear cache (model and persistent)
		clearCache(null, 'models');
		clearCache(null, 'persistent');

		$result = array('success' => true,
										'error' => null);
		$this->set('result', $result);
		
		$File = new File(TMP . Configure::read('Update.file'), false);
		$File->delete();
		
		if(!$this->RequestHandler->isAjax()) {
			$this->redirect(array('action' => 'notes'));
		}
	}
	
	function __checkUnzip() {
		if(class_exists('ZipArchive')) {
			return true;
		}
		
		exec('unzip', $output, $return_value);
		if($return_value === 0) {
			return true;
		}
		
		return false;
	}
}
?>