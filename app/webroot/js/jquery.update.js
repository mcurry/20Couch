(function($){
  if(!$.Update) {
    $.Update = new Object();
  }
	
	$.Update.go = function() {
		$.Update.download();
		
	}
	
	$.Update.download = function() {
		$.ajax({url: "download.json",
					 dataType: "json",
					 beforeSend: function() {
						$("#downloadStatus").html("<img src=../img/icons/ajax-loader.gif>");
					 },
					 success: function(data) {
					  if(!data.success) {
							$("#downloadStatus").html(data.error).addClass("error")
						} else {
							$("#downloadStatus").html("done").addClass("success");
							$.Update.extract();
						}
					 }
			});
	}
	
	$.Update.extract = function() {
		$.ajax({url: "extract.json",
					 dataType: "json",
					 beforeSend: function() {
						$("#extractStatus").html("<img src=../img/icons/ajax-loader.gif>");
					 },
					 success: function(data) {
					  if(!data.success) {
							$("#extractStatus").html(data.error).addClass("error")
						} else {
							$("#extractStatus").html("done").addClass("success");
							$.Update.process();
						}
					 }
			});
	}
	
	$.Update.process = function() {
		$.ajax({url: "process.json",
					 dataType: "json",
					 beforeSend: function() {
						$("#processStatus").html("<img src=../img/icons/ajax-loader.gif>");
					 },
					 success: function(data) {
					  if(!data.success) {
							$("#processStatus").html(data.error).addClass("error")
						} else {
							$("#processStatus").html("done").addClass("success");
							$("#update-done").show();
						}
					 }
			});
	}
})(jQuery);