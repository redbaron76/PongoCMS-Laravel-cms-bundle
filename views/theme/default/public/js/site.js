//SITE

//PongoCMS v2.0  jQuery Application Library
//2012-04-20 - copyright Fabio Fumis - Pongoweb.it

	
$.site = {

	fancyBox:
	function() {
		$('a[rel=fancybox]').fancybox();
	},

	nivoSlider:
	function() {
		$('#slider').nivoSlider();
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

	//SCROLL TOP
	$.site.scrollTop();

});