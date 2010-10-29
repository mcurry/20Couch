(function($){
  if(!$.ScrollRead){
    $.ScrollRead = new Object();
  }
	
	$.ScrollRead.init = function(options) {
		this.viewItemsTop = null;
		this.readItems = [];
				
		if($.Couch.isMobile) {
			$(window).bind("scroll.scrollMarkAsRead", function() {
				$.ScrollRead.scrolled();
			});			
		} else {
			$("#view-items").bind("scroll.scrollMarkAsRead", function() {
				if($.ScrollRead.viewItemsTop == null) {
					$.ScrollRead.viewItemsTop = $("#view-items").offset().top;
				}
				
				$.ScrollRead.scrolled();
			});
		}
		
		$.ScrollRead.scrolled = function() {
			if($.Couch.mode != "default") {
				return;
			}
			
			$last = null;
			$(".item-index > .item").each(function(i) {
				$this = $(this);

				if($.Couch.isMobile) {
					offset = $this.offset().top + $this.height() - $(window).scrollTop();
				} else {
					offset = $this.offset().top + $this.height() - $.ScrollRead.viewItemsTop;
				}
				
				if(offset < 0) {
					$last = $this;
				} else {
					if($last != null) {
						$last.addClass("read");
						itemId = new Array($last.data("meta")["Item"]["id"]);
						$last.find(".item").each(function() {
							$(this).addClass("read");
							itemId.push($(this).data("meta")["Item"]["id"]);
						});
						
						for(i = 0; i < itemId.length; i ++) {
							if($.ScrollRead.readItems.indexOf(itemId[i]) == -1) {
								$.ScrollRead.readItems.push(itemId[i]);
							}
						}
					}
					return false;
				}
			});

			if($.ScrollRead.readItems.length >= 10) {
				$.ScrollRead.send();
			}
		};

		$("#sources a").live('click', function(event) {
			$.ScrollRead.send();
		});
		
		$(window).unload(function() {
			$.ScrollRead.send();
		});
		
		$.ScrollRead.send = function() {
			if($.ScrollRead.readItems.length > 0) {
				$.ajax({
					type: "POST",
					url: $.Couch.baseUrl + "items/mark_as_read_by_id",
					data: {"data[Item][id]" : $.ScrollRead.readItems.toString() },
					global: false
				});
			}
			
			$.ScrollRead.readItems = [];
		}
	}
})(jQuery);

$(function(){
	$.ScrollRead.init();
});