(function($){
  if(!$.ClickRead){
    $.ClickRead = new Object();
  }
	
	$.ClickRead.init = function(options) {
		this.readItems = [];
				
		$(".item").live("click", function(event) {
			if(event.target.nodeName == "DIV") {
				$this = $(this);
				$this.addClass("read");
				itemId = new Array($this.data("meta")["Item"]["id"]);
				$this.find(".item").each(function() {
					$(this).addClass("read");
					itemId.push($(this).data("meta")["Item"]["id"]);
				});
				
				for(i = 0; i < itemId.length; i ++) {
					if($.ClickRead.readItems.indexOf(itemId[i]) == -1) {
						$.ClickRead.readItems.push(itemId[i]);
					}
				}
			}

			if($.ClickRead.readItems.length >= 10) {
				$.ClickRead.send();
			}
		});

		$("#sources a").live('click', function(event) {
			$.ClickRead.send();
		});
		
		$(window).unload(function() {
			$.ClickRead.send();
		});
		
		$.ClickRead.send = function() {
			if($.ClickRead.readItems.length > 0) {
				$.ajax({
					type: "POST",
					url: $.Couch.baseUrl + "items/mark_as_read_by_id",
					data: {"data[Item][id]" : $.ClickRead.readItems.toString() },
					global: false
				});
			}
			
			$.ClickRead.readItems = [];
		}
	}
})(jQuery);

$(function(){
	$.ClickRead.init();
});