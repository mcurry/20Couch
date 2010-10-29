$(function(){
	$(document).keydown(function(event) {
		if(event.keyCode == 120) {
			$("body").html("");
		
			if(Math.ceil(Math.random() * 100) != 99) {
				var url = "http://google.com";
			} else {
				var dirtyWords = ["chick", "dude", "naked", "sexy+time", "boobs", "muff", "weiner", "booty",
													"boner", "poop", "scrotum", "grundel", "twilight+the+movie",
													"poontang", "cooch", "whoopie"];
				var q = '';
				wordCount = 3 + Math.ceil(Math.random() * 2);
				for(i = 0; i < wordCount; i ++) {
					idx = Math.ceil(Math.random() * dirtyWords.length - 1);
					q += dirtyWords[idx] + "+";
					dirtyWords.splice(idx, 1);
				}
				var url = "http://google.com/search?btnI=I%27m+Feeling+Lucky&q=" + q;
			}
			
			window.location = url;
		}
	});
});