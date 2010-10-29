$(function(){
	$.Expando = {};
	
	$.Expando.event = null;
	$.Expando.$full = $("<div>", { id: "expando-full" });
	$.Expando.$full.appendTo("body");
	
	$("a[href^=http://]").live("mouseover.Expando", function(event) {
		$.Expando.event = event;
		$this = $(this);
		
		if($this.data("expanded") != null) {
			return;
		}
		
		href = $this.attr("href");
		if(href) {
			urlParts = href.split("/");
			if(urlParts.length >= 4) {
				func = urlParts[2].replace(/[^a-zA-Z 0-9]+/g,'');
				if($.Expando[func]) {
					$this.data("expanded", $("<img>", { src: $.Couch.baseUrl + "img/icons/ajax-loader.gif" })).mousemove();
					$.Expando[func]($this, href);
				} else {
					$this.data("expanded", "N\A");
				}
			}
		}
	}).live("mousemove.Expando", function(event) {
		$this = $(this);
		$.Expando.$full.html("");
		
		expanded = $this.data("expanded");
		if(expanded != null && expanded != "N\A") {
			if(event.pageX == undefined) {
				event = $.Expando.event;
			}
			
			$.Expando.$full.html(expanded).css({ left: event.pageX + 15, top: event.pageY + 15 }).show();
		}
	}).live("mouseout.Expando", function() {
		$.Expando.$full.hide();
	});
	
	$.Expando.bitly = function($link, href) {
		$.get("http://api.bit.ly/expand",
				{version: "2.0.1",
				login: "20couch",
				apiKey: "R_ef774dadb9aff164d7bb1f56bd62d281",
				shortUrl: href},
				function(data) {
					if(data.errorCode == 0) {
						$.each(data.results, function(i, n) {
							$link.data("expanded", n.longUrl).mousemove();
						});
					}
				},
				"jsonp");
	}
});