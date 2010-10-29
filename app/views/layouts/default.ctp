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
	<title><?php echo $title_for_layout; ?></title>
	<?php
		echo $this->element('layouts/static');
		echo $scripts_for_layout;
	?>
</head>
<body>
	<?php
		$class = '';
		if($mobile) {
			$class = ' class="mobile"';
		}
	?>

	<div id="container" data-role="page"<?php echo $class ?>>
		<?php
			if(!$mobile) {
				echo $this->element('layouts/menu');
			}
		?>
		
		<?php echo $content_for_layout; ?>
	</div>
	
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>