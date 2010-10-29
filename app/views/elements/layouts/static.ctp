<?php
	echo $this->element('layouts/meta');
	
	echo $html->script('http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js');

	if(empty($mobile)) {
		echo $html->script('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js');
		echo $html->css('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/themes/redmond/jquery-ui.css');
	} else {
		echo $html->script('http://code.jquery.com/mobile/1.0a1/jquery.mobile-1.0a1.min.js');
		echo $html->css('http://code.jquery.com/mobile/1.0a1/jquery.mobile-1.0a1.min.css');
	}
	
	echo $html->css($site->staticVersion('common'));
	
	echo $html->script($site->staticVersion('jquery.20couch'));
?>
	<script type="text/javascript">
		$(function(){
			$.Couch.baseUrl = "<?php echo Router::url('/') ?>";
			$.Couch.isMobile = <?php echo empty($mobile) ? $js->object(false) : $js->object(true) ?>;
			$.Couch.init({retweet_method: "<?php echo User::get('Setting.retweet_method') ?>",
									 defaultProviders: <?php echo $this->Js->object(User::get('Setting.defaultProviders')) ?>});
		});
	</script>

<?php
	if(empty($mobile)) {
		echo $html->css($site->staticVersion('style'));
		echo $html->script($site->staticVersion('jquery.20couch-full'));
	} else {
		echo $html->script($site->staticVersion('jquery.20couch-mobile'));
	}
	
	
	foreach($plugins as $plugin) {
		if($pluginJs = $plugin->js()) {
			echo $html->script($pluginJs);
		}
		
		if($pluginCss = $plugin->css()) {
			echo $html->css($pluginCss);
		}
	}
?>