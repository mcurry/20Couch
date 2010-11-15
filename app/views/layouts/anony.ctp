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
<!DOCTYPE html> 
<html>
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->element('layouts/meta');

		if(!empty($mobile)) {
			echo $html->script('http://ajax.microsoft.com/ajax/jQuery/jquery-1.4.4.min.js');
			echo $html->script('http://code.jquery.com/mobile/1.0a2/jquery.mobile-1.0a2.min.js');
			echo $html->css('http://code.jquery.com/mobile/1.0a2/jquery.mobile-1.0a2.min.css');
		} else {
			echo $html->css($site->staticVersion('anony'));
		}

		echo $scripts_for_layout;
	?>
</head>
<body>
	<div id="anony" data-role="page">
		<?php echo $content_for_layout; ?>
		
		<div id="footer" data-role="footer" role="banner">
			<?php echo $html->link('20Couch.com', 'http://20couch.com', null, null, false) ?>
			<?php echo $html->link('matt@20couch.com', 'mailto:matt@20couch.com') ?>
			<?php echo $html->link('A PseudoCoder.com Production', 'http://www.pseudocoder.com', null, null, false) ?>
		</div>		
	</div>
	

</body>
</html>
