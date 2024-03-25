$(document).ready(function(){
	var div = $(".hook-bannergroup-slider");
	if( $(".slider div", div).length > 1 ){
		$.getScript("/external/cycle.js", function(){
			$(".slider", div).cycle({
				fx: 'scrollHorz',
				speed:'slow',
				timeout:7000,
				next: $('.control .arrow.right', div),
				prev: $('.control .arrow.left' , div),
				pager: $('.nav', div), 
				pause: true
			});
		})
	}
})