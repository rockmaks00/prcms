$(document).ready(function(){
	var div = $(".hook-bannergroup-carousel");
	if( $(".carousel div", div).length > 1 ){
		
		$.getScript("/external/jcarousellite/jcarousellite_1.0.1.pack.js", function(){
			$(".carousel", div).jCarouselLite({
				btnNext: $('.control .arrow.right', div),
				btnPrev: $('.control .arrow.left' , div),
				visible: 2
			});
		})
	}
})