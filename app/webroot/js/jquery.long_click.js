/*
 * jQuery Long Click Plugin
 * Copyright (c) 2010 Matt Curry
 * www.PseudoCoder.com
 * http://github.com/mcurry/jquery_long_click
 *
 * @author      Matt Curry <matt@pseudocoder.com>
 * @license     MIT
 *
 */
(function($){
    $.LongClick = function(el, options){
        var base = this;
				
        base.$el = $(el);
        base.el = el;
        
        base.$el.data("LongClick", base);
				
				base.timeout = null;
				base.event = null;
        
        base.init = function(){
						if(typeof options == "function") {
								options = { callback: options };
						}
						
            base.options = $.extend({} ,$.LongClick.defaultOptions, options);
						base.$el.bind('callback.longClick', base.options.callback);
						
						base.$el.bind("mousedown.longClick", function(event) {
								base.event = event;
								base.timeout = setTimeout (base.callback, base.options.delay);
			
								$(document).bind("mousemove.longClick", function(){
										base.clear();
								});

								base.$el.bind("mouseup.longClick", function() {
										base.clear();
								});
						});
						
						base.callback = function() {
								base.clear();
								base.$el.trigger("callback.longClick", base.event);
						}
						
						base.clear = function() {
								window.clearTimeout(base.timeout);
								$(document).unbind("mousemove.longClick");
								base.$el.unbind("mouseup.longClick");								
						}
        };
        base.init();
    };
    
    $.LongClick.defaultOptions = {
				callback: function() {},
				delay: 500
    };
    
    $.fn.longClick = function(options){
        return this.each(function(){
            (new $.LongClick(this, options));
        });
    };
    
})(jQuery);