$(function(){
	$("a[href^=http]").attr("rel", "external").live("click", function(event) {
		event.preventDefault();
		window.open($(this).attr("href"));
	});
});