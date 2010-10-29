(function($){
	$.Couch.init = function(options) {
		this.options = options;
		
		this.namedView = null;
		this.providerUpdateUrl = null;
		this.providerPage = 1;
		this.ajaxObj = null;
		this.mode = "default";
		this.statusText = "";
		this.paginating = 0;
		this.selectedProvider = null;
		this.viewdTimestamp = null;

		$.ajaxSetup({
			cache: false,
		});

		$("body").ajaxSend(function (event, XMLHttpRequest) {
			if($.Couch.ajaxObj != null) {
				$.Couch.ajaxObj.abort();
			}
			$.Couch.ajaxObj = XMLHttpRequest;
			$.Couch.setStatus($.Couch.statusText);
		});

		$("body").ajaxComplete(function() {
			$.Couch.ajaxObj = null;
			$.Couch.clearStatus();
		});
		
		this.status = $("#status");
		this.sources = $("#sources");
		this.providers = $("#providers");
		this.viewActions = $("#view-actions");
		this.viewItems = $("#view-items");
		this.viewUpdateSingle = $("#view-update-single");
		this.viewCount = $(".view-detail-count");
		this.providerAll = $("#provider-all");
		this.providerUpdateAll = $("#provider-update-all");
		this.viewName = $("#view-name");
		this.viewDelete = $("#view-delete");
		this.charsRemaining = $("#chars-remaining");
		this.charsRemainingWrapper = $("#chars-remaining-wrapper");
		this.twitterApiRequestsRemaining = $("#twitter-api-requests-remaining");
		
		this.initResize();
		this.initButtons();
		this.initShowActions();
		this.initStatusUpdate();
		this.initProviderAdd();
		this.initProviderMarkAsRead();
		this.initViewUpdate();
		this.initViewAllUpdate();
		this.initViewUnreadUpdate();
		this.initProviderAutoUpdate();
		this.initProviderSelect();
		this.initProviderDelete();
		this.initHideForwards();
		this.initHoverArchive();
		
		this.initReply();
		if(options.retweet_method == "api") {
			this.initForwardApi();
		} else {
			this.initForwardManual();
		}
		
		this.initMute();
		this.initTrackReplies();
		this.initUnfollow();
		
		this.initSearch();
		
		last_provider_id = this.readCookie("last_provider_id");
		if(last_provider_id != null && this.sources.find("#" + last_provider_id)) {
			this.sources.find("#" + last_provider_id).click();
		} else {
			this.sources.find("#providers a:first").click();
		}
	}

	$.Couch.setStatus = function(msg) {
		this.status.html(msg).show();
		this.status.css("left", ($(window).width() / 2) - (this.status.width() / 2));
	}
	$.Couch.clearStatus = function() {
		this.status.html("").hide();
	}
	
	$.Couch.initResize = function() {
		$(document.body).css("overflow", "hidden");
		
		$(window).resize(function(){
			winHeight = $(window).height();
			
			$.Couch.sources.height(winHeight - $.Couch.sources.position().top);
			$.Couch.viewItems.height(winHeight - $.Couch.viewItems.position().top);		
		}).resize();	
	}

	$.Couch.initButtons = function() {
		$("a#update-status-link").button({icons: { primary: 'ui-icon-circle-triangle-e' }});
		$("a#add-provider-link").button({icons: { primary: 'ui-icon-plus' }});
		$("a#provider-update-all").button({icons: { primary: 'ui-icon-refresh' }});
		$("a#view-update-single").button({icons: { primary: 'ui-icon-refresh' }});
		$("#view-delete").button({disabled: true, icons: { primary: 'ui-icon-trash' }});
	}
	
	$.Couch.initShowActions = function() {
		$(".actions-control").live("click", function(event) {
			event.preventDefault();
			$this = $(this);
			
			if($this.children("span.ui-icon").hasClass("ui-icon-plus")) {
				$this.closest(".item-info").children(".actions:first").slideDown();
				$this.children("span.ui-icon").removeClass("ui-icon-plus").addClass("ui-icon-minus");
			} else {
				$this.closest(".item-info").children(".actions:first").slideUp();
				$this.children("span.ui-icon").removeClass("ui-icon-minus").addClass("ui-icon-plus");
			}
		});
	}
	
	$.Couch.initProviderSelect = function() {
		$("#sources a").live('click', function(event) {
			event.preventDefault();
			
			$.Couch.viewDelete.button( "option", "disabled", true );
			$.Couch.mode = "default";
			$this = $(this);
			
			$.Couch.SelectedProvider = $this;
			$("#ItemSearch").val("");
			
			if($this.parent().parent().hasClass("service-1")) {
				$("#ProviderProviderId").val($this.attr("id").replace("provider-", ""));
			}
			
			$.Couch.createCookie("last_provider_id", $this.attr("id"), 365);
			$.Couch.viewActions.show();
			$.Couch.viewItems.empty();
			$.Couch.statusText = "Loading...";
			$("#sources a").removeClass("selected");
			$this.addClass("selected");
			$.Couch.viewName.html($this.html()).parent().removeClass("search");
			
			$.Couch.providerUpdateUrl = $this.attr("href");
			$.Couch.viewUpdateSingle.attr("href", $.Couch.providerUpdateUrl + "/force:true");

			$.Couch.providerPage = 1;
			$.Couch.namedView = $this.attr("href").split("/").pop();
			
			$.ajax({
				type: "GET",
				url: $.Couch.providerUpdateUrl,
				dataType: "html",
				success: function(data) {
					$.Couch.viewItems.html(data);
				}
			});
			
		});
	}
	
	$.Couch.initProviderDelete = function() {
		$.Couch.viewDelete.click(function(event) {
			event.preventDefault();
			
			$.Couch.setStatus("Deleting...");
			$.ajax({
				type: "GET",
				url: $(this).attr("href") + ".json",
				dataType: "json",
				success: function(data) {
					if(data.success) {
						$.Couch.providerAll.click();
						$("#provider-" + data.id).remove();
					}
				},
				global: false
			});
			
		});
	}
	
	$.Couch.initViewItemsScroll = function() {
		$.Couch.viewItems.bind("scroll.couch", function(event) {
			if(($.Couch.viewItems[0].scrollHeight - $.Couch.viewItems.height()) / $.Couch.viewItems.scrollTop() < 1.1) {
				$.Couch.viewItems.unbind("scroll.couch");
				$.Couch.setStatus("Loading...");
				$.Couch.providerPage ++;
				
				$.ajax({
					type: "GET",
					url: $.Couch.providerUpdateUrl + "/page:" + $.Couch.providerPage,
					dataType: "html",
					global: false,
					success: function(data) {
						$.Couch.viewItems.append(data);
					}
				});
			}
		});
	}

	$.Couch.initViewUpdate = function() {
		$(".provider-update").click(function(event) {
			event.preventDefault();
			viewUpdate(this);
		});
		
		$.Couch.providerUpdateAll.click(function(event) {
			event.preventDefault();
			$.Couch.statusText = "Loading...";
			autoUpdate({global: true});	
		});
	}

	$.Couch.initViewAllUpdate = function() {
		$(".view-all").live("click", function(event) {
			event.preventDefault();
			$.Couch.mode = "view-all";
			$.Couch.providerUpdateUrl = $(this).attr("href");
			viewUpdate(this);
		});
	}

	$.Couch.initViewUnreadUpdate = function() {
		$(".view-unread").live("click", function(event) {
			event.preventDefault();
			$.Couch.mode = "default";
			$.Couch.providerUpdateUrl = $(this).attr("href");
			viewUpdate(this);
		});
	}
	
	function viewUpdate (elem) {
			$.Couch.statusText = "Loading...";
			$.Couch.viewItems.empty();

			$.ajax({
				type: "GET",
				url: $(elem).attr("href"),
				dataType: "html",
				success: function(data) {
					$.Couch.viewItems.append(data);
				}
			});
	}
	
	function autoUpdate(options) {	
		if(options == undefined) {
			options = {};
		}
		
		baseOptions = {global: false};
		options = $.extend({}, baseOptions, options);
		
		// don't do auto update if another request is running
		if(!options['global'] && $.Couch.ajaxObj != null) {
			return;
		}
		
		$.ajax({
			type: "GET",
			url: $.Couch.providerUpdateAll.attr("href") + "/" + $.Couch.namedView + "/auto:true/paginating:" + $.Couch.paginating + "/mode:" + $.Couch.mode + "/timestamp:" + encodeURI($.Couch.viewdTimestamp),
			dataType: "html",
			global: options['global'],
			success: function(data) {
				if(options['global'] == true || $.Couch.ajaxObj == null) {
					$.Couch.viewItems.append(data);
				}
				
				updateTimes();
			}
		});		
	}
	
	$.Couch.initProviderAutoUpdate = function() {
		setTimeout(function() {
			autoUpdate();
		}, 5000);

		setInterval (function() {
				autoUpdate();
			}, 60000);
	}
	
	function updateTimes() {
		var dateObj = new Date();
		var now = Math.round(dateObj.getTime() / 1000);
		
		$(".item").each(function() {
			vItem = $(this).data("meta");
			if(vItem == undefined) {
				return true;
			}
			
			vItem = vItem["Item"];
			diff = now - vItem.posted;

			if ( diff < 0 || diff >= 2592000 ) {
				return true;
			}
			
			if(diff < 60) {
				timeAgo = "just now";
			} else if(diff < 3600) {
				timeAgo = Math.round(diff / 60) + " minutes ago";
			} else if(diff < 86400) {
				timeAgo = Math.round(diff / 60 / 60) + " hours ago";
			} else {
				timeAgo = Math.round(diff / 60 / 60 / 24) + " days ago";
			}

			$(this).find(".time-ago:first").html(timeAgo);
		});
	}
	
	
	function checkAccount() {
		if($("#ProviderProviderId option").length == 0) {
			$.Couch.setStatus("You must add a twitter account before sending a tweet");
			return false;
		}
		
		return true;
	}
	
	$.Couch.statusReset = function() {
		$("#ProviderItemId").val("");
		$("#ProviderAction").val("");
		$("#ProviderStatus").attr("readonly", false);
		if($("#ProviderStatus").val().match(/^@[\w]+ $/gmi)) {
			$("#ProviderStatus").val("");
		}
		
		if($.Couch.options.defaultProviders[$.Couch.SelectedProvider.attr("id")] != undefined) {
			$("#ProviderProviderId").val($.Couch.options.defaultProviders[$.Couch.SelectedProvider.attr("id")] );
		}
		
		//$.Couch.options
		
		$.Couch.charsRemainingWrapper.attr("class", "");
		$('#update-status').unbind("dialogopen").bind("dialogopen", function() { $("#ProviderStatus").focus(); });
	}
	
	$.Couch.initStatusUpdate = function() {
		$("#update-status").dialog({autoOpen: false, modal: true, width: 375});
		
		$("#ProviderStatus").autocomplete({
			source: function(request, response) {
				if(request.term.indexOf(" ") != -1) {
					response([]);
					return;
				}
				
				$.ajax({
					url: $.Couch.baseUrl + "origins/autocomplete/name:" +  request.term + "/provider_id:" + $("#ProviderProviderId").val() + ".json",
					dataType: "json",
					global: false,
					success: function(data) {
						response(data);
					}
				})
			}
		});
		
		//$("#ProviderProviderId").val(data.Item.provider_id);
		
		$("#ProviderProviderId").change(function(event) {
			$.ajax({
				type: "POST",
				url: $.Couch.baseUrl + "settings/update.json",
				data: { "data[Setting][key]": "defaultProviders." + $.Couch.SelectedProvider.attr("id"), "data[Setting][value]": $(this).val() },
				global: false,
				success: function(data) {
					$.Couch.viewItems.append(data);
				}
			});
		});
		
		$("#update-status-link").click(function(event) {
			event.preventDefault();
			if(checkAccount()) {
				$.Couch.statusReset();
				$("#update-status").dialog("open");
			}
		});
		
		$("#ProviderStatus").keyup(function() {
			charsRemaining = 140 - this.value.length;
			$.Couch.charsRemaining.html(charsRemaining);
			if(charsRemaining < 20 && charsRemaining >=0) {
				$.Couch.charsRemainingWrapper.attr("class", "getting-close");
			} else if(charsRemaining < 0) {
				$.Couch.charsRemainingWrapper.attr("class", "over-limit");
			} else {
				$.Couch.charsRemainingWrapper.attr("class", "");
			}
		});
		
		$("#update-status-form").submit(function() {
			$.Couch.setStatus("Sending...");
			$("#update-status").dialog("close");
		
			$this = $(this);

			$.ajax({
				type: "POST",
				url: $this.attr("action") + ".json",
				dataType: "json",
				data: $this.serialize(),
				success: function() {
					$("#ProviderStatus").val("").keyup();
				}
			});
				
			return false;
		});	
	}
	
	$.Couch.initProviderAdd = function() {
		$("#tabs").tabs();
		$("#add-provider").dialog({autoOpen: false, modal: true, width: 375});
		$("#add-provider-link").click(function() {
			$("#add-provider").dialog("open");
			return false;
		});
		
		$(".provider-add-form").submit(function(event) {
			event.preventDefault();
			
			$.Couch.setStatus("Saving...");
			$("#add-provider").dialog("close");
		
			$this = $(this);

			$.ajax({
				type: "POST",
				url: $this.attr("action") + ".json",
				dataType: "json",
				data: $this.serialize(),
				success: function(data) {
					if(data.success) {
						updateProviders(data);
					} else {
						$("#add-provider").dialog("open");
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					$("#add-provider").dialog("open");
				},
				global: false
			});
		});	
	}
	
	function updateProviders(data, options) {
		if(options == undefined) {
			options = {};
		}
		
		baseOptions = {select: true};
		options = $.extend({}, baseOptions, options);
		
		$(".provider-add-form input[type=text]").val("");
		
		$(".service-" + data.provider.Provider.service_id).parent().removeClass("empty");
		li = "<li><a id=\"provider-" + data.provider.Provider.id + "\" href=\"items/index/provider_id:" + data.provider.Provider.id + "\">" + data.provider.Provider.name + " (<span class='item-count'>0</span>)</a></li>";
		inserted = false;
		$.each($(".service-" + data.provider.Provider.service_id + " li"), function(i, v) {
			$v = $(v);
			if($v.children("a").html() > data.provider.Provider.name) {
				$v.before(li);
				inserted = true;
				return false;
			}
		});
		if(!inserted) {
			$(li).appendTo(".service-" + data.provider.Provider.service_id);
		}
		
		if(options.select) {
			$("#provider-" + data.provider.Provider.id).click();
		}
	
		if(data.provider.Provider.service_id == 1) {
			option = "<option value='" + data.provider.Provider.id + "'>" + data.provider.Provider.name + "</option>";
			inserted = false;
			
			$.each($("#ProviderProviderId option"), function(i, v) {
				$v = $(v);
				if($v.html() > data.provider.Provider.name) {
					$v.before(option);
					inserted = true;
					return false;
				}
			});
	
			if(!inserted) {
				$(option).appendTo("#ProviderProviderId");
			}
		}
	}
	
	$.Couch.initProviderMarkAsRead = function() {
		$("#view-mark-as-read").button().click(function() {
			markAsRead($.Couch.baseUrl + "items/mark_as_read");
		})
		.next()
		.button({
			text: false,
			icons: {
				primary: "ui-icon-triangle-1-s"
			}
		})
		.toggle(function() {
			$("#view-mark-as-read-expanded").show();
		},
		function() {
			$("#view-mark-as-read-expanded").hide();
		})
		.parent()
		.buttonset();
		
		$range = $("#view-mark-range-as-read");
		$expanded = $("#view-mark-as-read-expanded");
		
		coords = $range.offset();
		coords["top"] += Math.floor($range.height() + 2);
		coords["left"] = Math.floor(coords["left"] - $expanded.width() + $range.width());
		$expanded.offset(coords);
		
		$("#view-mark-as-read-expanded a").click(function(event) {
			event.preventDefault();
			markAsRead($(this).attr("href"));
			$("#view-mark-range-as-read").click();
		});
	}
	
	function markAsRead(url) {
		$.Couch.setStatus("Marking as read...");
		$.Couch.viewItems.empty();
		$.ajax({
			type: "GET",
			url: url + "/" + $.Couch.namedView,
			global: false,
			dataType: "html",
			success: function(data) {
				$.Couch.viewItems.append(data);
			}
		});
	}

	$.Couch.initReply = function() {
		$("a.reply").live("click", function(event) {
			event.preventDefault();
						
			if(checkAccount()) {
				$.Couch.statusReset()
				$this = $(this);
				
				data = $this.closest(".item").data("meta");
				$("#ProviderAction").val("reply");
				$("#ProviderItemId").val($this.attr("href").split("/").pop());
				if(data.Item.service_id == 1) {
					$("#ProviderProviderId").val(data.Item.provider_id);
				}
				
				if(data.Item.message == 1) {
					replyType = "d ";
				} else {
					replyType = "@";
				}
				$("#ProviderStatus").val(replyType + data.Origin.name + " ").keyup();
				
				$("#update-status").dialog("open");
			}
		});
	}

	$.Couch.initHideForwards = function() {
		$("#ItemHideRetweets").click(function(event) {
			if($(this).is(":checked")) {
				$(".forwarded").hide();
			} else {
				$(".forwarded").show();
			}
		});
	}
	
	$.Couch.initHoverArchive = function() {
		$(".archive").live('hover', function(event) {
			if (event.type == 'mouseover') {
				$(this).fadeTo(0, 1);
			} else {
				$(this).fadeTo(0, .5);
			}
		});
	}
	
	$.Couch.initForwardManual = function() {
		$("a.forward").live("click", function(event) {
			event.preventDefault();
			
			if(checkAccount()) {
				$.Couch.statusReset();
				
				data = $this.closest(".item").data("meta");
				$("#ProviderAction").val("forwardManual");
				$("#ProviderItemId").val($this.attr("href").split("/").pop());
				$("#ProviderProviderId").val(data.Item.provider_id);
				$("#ProviderStatus").val("RT @" + data.Origin.name + ": " + data.Item.text).keyup();
				$("#update-status").dialog("open");
			}
		});
	}

	$.Couch.initForwardApi = function() {
		$("a.forward").live("click", function(event) {
			event.preventDefault();
			
			if(checkAccount()) {
				$.Couch.statusReset();

				data = $this.closest(".item").data("meta");
				$("#ProviderAction").val("forwardApi");
				$("#ProviderItemId").val($(this).attr("href").split("/").pop());
				$("#ProviderProviderId").val(data.Item.provider_id);
				$("#ProviderStatus").val(data.Item.text).attr("readonly", true).keyup();
				
				$("#update-status").unbind("dialogopen").bind("dialogopen", function() { $("#update-status-form").children(".submit").children("input").focus(); });
				$("#update-status").dialog("open");
			}
		});
	}
	
	$.Couch.initTrackReplies = function() {
		$("#provider-track-replies").dialog({autoOpen: false, modal: true, width: 375});

		$(".track-replies").live("click", function(event) {
			event.preventDefault();
			$("#ProviderTrackReplyId").val($(this).attr("href").split("/").pop());
			$("#provider-track-replies").dialog("open");
		});
		
		$("#provider-track-replies-form").submit(function(event) {
			event.preventDefault();
			$.Couch.setStatus("Tracking...");
			$("#provider-track-replies").dialog("close");

			$this = $(this);
			$.ajax({
				type: "POST",
				url: $this.attr("action") + ".json",
				global: false,
				dataType: "json",
				data: $this.serialize(),
				success: function(data) {
					if(data.success) {
						updateProviders(data, {select: false} );
						$.Couch.clearStatus();
					} else {
						$.Couch.setStatus("Something bad happened.");
					}
				}
			});
		});	
	}
	
	$.Couch.initMute = function() {
		$("#origin-mute").dialog({autoOpen: false, modal: true, width: 375});

		$(".mute").live("click", function(event) {
			event.preventDefault();
			$("#OriginMuteId").val($(this).attr("href").split("/").pop());
			$("#origin-mute").dialog("open");
		});
		
		$("#origin-mute-form").submit(function(event) {
			event.preventDefault();
			
			$.Couch.setStatus("Muting...");
			$("#origin-mute").dialog("close");
			$.Couch.removeOrigin($("#OriginMuteId").val());

			$this = $(this);
			$.ajax({
				type: "POST",
				url: $this.attr("action") + ".json",
				global: false,
				dataType: "json",
				data: $this.serialize()
			});
				
			return false;
		});	
	}

	$.Couch.initUnfollow = function() {
		$("#origin-unfollow").dialog({autoOpen: false, modal: true, width: 375});

		$(".unfollow").live("click", function(event) {
			event.preventDefault();
			$("#OriginUnfollowId").val($(this).attr("href").split("/").pop());
			$("#unfollow-name").html($(this).closest(".item").data("meta")["Origin"]["name"]);
			$("#origin-unfollow").dialog("open");
		});
		
		$("#origin-unfollow-form").submit(function() {
			$.Couch.setStatus("Unfollowing...");
			$("#origin-unfollow").dialog("close");
			$.Couch.removeOrigin($("#OriginUnfollowId").val());
			
			$this = $(this);
			$.ajax({
				type: "POST",
				url: $this.attr("action") + ".json",
				global: false,
				dataType: "json",
				data: $this.serialize()
			});
				
			return false;
		});	
	}
	
	$.Couch.removeOrigin = function(origin_id) {
		$toBeRemoved = $(".origin-" + origin_id);
		
		$.Couch.providerAll.children(".item-count").html($.Couch.providerAll.children(".item-count").html() - $toBeRemoved.length);
		$.Couch.viewCount.html($.Couch.viewCount.html() - $toBeRemoved.length);
		$.Couch.SelectedProvider.children(".item-count").html($.Couch.SelectedProvider.children(".item-count").html() - $toBeRemoved.length);
				
		$toBeRemoved.remove();
	}
	
	$.Couch.initSearch = function() {	
		$("#search").submit(function(event) {
			event.preventDefault();
			
			$.Couch.viewDelete.button( "option", "disabled", true );
			$.Couch.statusText = "Loading...";
			$this = $(this);

			$.ajax({
				type: "POST",
				url: $this.attr("action"),
				data: $this.serialize(),
				success: function(data) {
					$.Couch.mode = "search";
					$.Couch.viewItems.html(data);
				}
			});
		});	
	}
	
	$.Couch.updateSelectedProvider = function(data) {
		if(data.counts) {
			$.Couch.updateCounts(data);
		}
		
		if(data.view) {
			if(data.view.id != "all" && data.view.id != "search") {
				$.Couch.viewDelete.button( "option", "disabled", false );
			}
			
			$.Couch.updateItems(data);
		}	
	}
	
	$.Couch.updateCounts = function(data) {
		$.Couch.providerAll.children(".item-count").html(data.counts.all);

		$.each(data.counts.sources, function(type, source) {
			$.each(source, function(i, row) {
				$source = $("#" + type + "-" + row.id).children(".item-count");
				oldCount = $source.html();
				if(oldCount != row.item_count) {
					$source.html(row.item_count).closest("li").effect("highlight", {}, "slow");
				}
			});
		});
	}
	
	$.Couch.updateItems = function(data) {
		if(data.view.hasNextPage) {
			$.Couch.initViewItemsScroll();
			$.Couch.paginating = 1;
		} else if(!data.auto) {
			$.Couch.viewItems.unbind("scroll.couch");
			$.Couch.paginating = 0;
		}

		$("#ViewdId").val(data.view.id);
		$("#ViewdType").val(data.view.type);
		$.Couch.viewdTimestamp = data.timestamp;

		if(data.view.name != undefined) {
			$.Couch.viewName.html(data.view.name);
			$.Couch.viewDelete.attr("href", $.Couch.baseUrl + "providers/delete/" + data.view.id);
			$.Couch.viewCount.html(data.view.item_count);
			document.title = "20Couch - " + data.view.name + " (" + data.view.item_count + ")";
		}
		
		if($("#ItemHideRetweets").is(":checked")) {
			$(".forwarded").hide();
		}
		
		if(data.view.all || $.Couch.mode == "view-all") {
			$("#view-unread-link-wrapper .item-link").css("display", "inline").children("a").attr("href", $.Couch.providerUpdateUrl.replace("/view:all", ""));
			$("#view-unread-link-wrapper .item-text").hide();
			$("#view-all-link-wrapper .item-text").css("display", "inline");
			$("#view-all-link-wrapper .item-link").hide();
			document.title = "20Couch - " + data.view.name + " (All Items)";
		} else {
			$("#view-all-link-wrapper .item-link").css("display", "inline").children("a").attr("href", $.Couch.providerUpdateUrl + "/view:all");
			$("#view-all-link-wrapper .item-text").hide();
			$("#view-unread-link-wrapper .item-link").hide();
			$("#view-unread-link-wrapper .item-text").css("display", "inline");
		}
	}
	
	//Cookie functions taken from http://www.quirksmode.org/js/cookies.html
	$.Couch.createCookie = function(name,value,days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}
		else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
	}

	$.Couch.readCookie = function(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');

		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) {
				if(c.length > nameEQ.length) {
					return c.substring(nameEQ.length,c.length);
				} else {
					return null;
				}
			}
		}
		return null;
	}
})(jQuery);