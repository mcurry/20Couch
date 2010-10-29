$(function(){
	$viewItems = $("#view-items");
	
	$(document).keydown(function(e) {
		if(e.target.type == 'textarea' || e.target.type == 'text' || e.target.type == 'text') {
			return;
		}
		
		itemTop = null;

		switch(e.which) {
			case 74:
				$item = $viewItems.find(".selected:first");
				if($item.length == 0) {
					$item = $viewItems.find(".item:first").addClass("selected");
				} else {
					$next = $item.removeClass("selected").next();
					if($next.length == 0) {
						$next = $item.parent().next(".item-index").children(".item:first")
					}
					
					if($next.length > 0) {
						$next.addClass("selected");
						$item = $next;
					}
				}
				
				if($item.length > 0) {
					itemTop = $item.offset().top - 100;
				}
				break;
			
			case 75:
				$item = $viewItems.find(".selected:first");
				if($item.length > 0) {
					$prev = $item.removeClass("selected").prev();
					if($prev.length == 0) {
						$prev = $item.parent().prev(".item-index").children(".item:last")
					}
					
					if($prev.length > 0) {
						$prev.addClass("selected");
						$item = $prev;
					}
					
					if($item.length > 0) {
						itemTop = $item.offset().top - 232;
					}
				}
				break;
		}
		
		if(itemTop) {
			scrollTop = $viewItems.scrollTop() + itemTop;
			
			if(scrollTop < 0) {
				scrollTop = 0;
			}
			
			$viewItems.scrollTop(scrollTop);
		}
	});
});