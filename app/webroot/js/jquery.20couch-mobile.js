(function($){
 	$.Couch.init = function(options) {
		this.actionsEntry = null;
	}
})(jQuery);

$('div').live('pagecreate',function(){
  $("a[href^=http]").attr("rel", "external");
});