<?php
/* SVN FILE: $Id$ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $html->meta('icon', 'favicon.png');
		echo $html->css($site->staticVersion('style'));
		
		echo $html->script('http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js');
		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="container">
		<?php echo $this->element('layouts/menu') ?>
		<div id="settings">
				<h2>
					<?php __('Settings') ?>
					<?php echo $html->link('&laquo; ' . __('Back to 20Couch', true), '/', array('escape' => false)) ?>
				</h2>
				
				<?php echo $session->flash(); ?>
			
				<?php
					$class = array('Settings/index' => '',
												 'Plugins/index' => '',
												 'Providers/index' => '',
												 'Origins/index' => '',
												 'Update/index' => '');
					$class[$this->name . '/' . $this->action] = 'selected';
				?>
				<ul class="options">
					<li><?php echo $html->link(__('Settings', true), array('controller' => 'settings', 'action' => 'index'), array('class' => $class['Settings/index'])) ?></li>
					<li><?php echo $html->link(__('Plugins', true), array('controller' => 'plugins', 'action' => 'index'), array('class' => $class['Plugins/index'])) ?></li>
					<li><?php echo $html->link(__('Subscriptions', true), array('controller' => 'providers', 'action' => 'index'), array('class' => $class['Providers/index'])) ?></li>
					<li><?php echo $html->link(__('Mutes', true), array('controller' => 'origins', 'action' => 'index'), array('class' => $class['Origins/index'])) ?></li>
					<li><?php echo $html->link(__('Update', true), array('controller' => 'update', 'action' => 'index'), array('class' => $class['Update/index'])) ?></li>
				</ul>
				
				<div class="options-detail">
					<?php echo $content_for_layout; ?>
				</div>
			</div>
	</div>
	
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
