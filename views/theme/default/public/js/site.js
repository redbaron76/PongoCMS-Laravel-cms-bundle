//SITE

//PongoCMS v2.0  jQuery Application Library
//2012-04-20 - copyright Fabio Fumis - Pongoweb.it

	
$.site = {

	analytics:
	function(code) {
		if(code.length > 0) {
			$.trackPage(code);
		}
	},
	
	fancyBox:
	function() {
		$('a[rel=fancybox]').fancybox();
	},

	nivoSlider:
	function() {
		$('.banner').nivoSlider();
	},

	scrollTop:
	function() {
		$('a[href=#top]').click(function(){
			$('html, body').animate({scrollTop:0}, 'slow');
			return false;
		});
	},

}

//RUN

$(function() {
	
	//GOOGLE ANALYTICS
	$.site.analytics(ANALYTICS_ID);

	//SCROLL TOP
	$.site.scrollTop();

});
