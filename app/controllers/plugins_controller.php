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
class PluginsController extends AppController {
	var $name = 'Plugins';
	var $layout = 'settings';

	function index() {
		//get list of plugins
		$plugins = $this->Plugin->find('all');
		$knownPlugins = Set::extract('/Plugin/path', $plugins);

		//check for new and remove deleted
		App::import('Core', 'Folder');
		$Folder = new Folder(Configure::read('Plugin.path'));
		$allPlugins = $Folder->read(true, array('.', 'find'));
		if (!empty($allPlugins[0])) {
			foreach($allPlugins[0] as $i => $newPlugin) {
				if (!in_array($newPlugin, $knownPlugins)) {
					$NewPlugin = $this->Plugin->load($newPlugin);
					$this->Plugin->create();
					$this->Plugin->save(array('name' => $NewPlugin->name,
																		'description' => $NewPlugin->description,
																		'author' => $NewPlugin->author,
																		'link' => $NewPlugin->link,
																		'version' => $NewPlugin->version,
																		'path' => $newPlugin,
																		'active' => false));
				}
			}
		}
		
		if (!empty($plugins)) {
			foreach($plugins as $i => $plugin) {
				if (empty($allPlugins[0]) || !in_array($plugin['Plugin']['path'], $allPlugins[0])) {
					$this->Plugin->delete(array('id' => $plugin['Plugin']['id']));
				} else {
					$OldPlugin = $this->Plugin->load($plugin['Plugin']['path']);
					$plugin['Plugin']['version'] = $OldPlugin->version;
					$plugin['Plugin']['description'] = $OldPlugin->description;
					$plugin['Plugin']['author'] = $OldPlugin->author;
					$plugin['Plugin']['link'] = $OldPlugin->link;
					$this->Plugin->save($plugin);
				}
			}
		}		

		$this->helpers[] = 'Time';
		$this->set('pluginsList', $this->paginate());
	}

	function activate($id) {
		if ($id) {
			if ($this->Plugin->save(array('id' => $id, 'active' => true))) {
				$this->Session->setFlash(__('Activated plugin.', true));
			} else {
				$this->Session->setFlash(__('Error activating plugin.', true));
			}
		}

		$this->redirect('index');
	}

	function deactivate($id) {
		if ($id) {
			if ($this->Plugin->save(array('id' => $id, 'active' => false))) {
				$this->Session->setFlash(__('Deactivated plugin.', true));
			} else {
				$this->Session->setFlash(__('Error deactivating plugin.', true));
			}
		}

		$this->redirect('index');
	}
}
?>