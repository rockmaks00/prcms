$(function(){
	$(".hook-feedback-default").each(function(){
		var block = $(this),
			form = $("form", block),
			overlay = $(".overlay", block),
			warning = $(".warning", block),
			toggleOverlayClass = function(cl){
				overlay.removeClass("success error sending").addClass(cl);
			};
		overlay.on("click",function(e){
			e.preventDefault();
			if( $(this).hasClass("sending") ) return false;
			toggleOverlayClass();
		});
		form.ajaxForm({
			type: "POST",
			dataType: 'json',
			url: form.prop("action")+"ajax/",
			beforeSubmit: function(arr){
				var check = true;
				for(var i in arr){
					var el = $('[name="'+arr[i].name+'"]', block)
					if( el.data("required") && !arr[i].value ){
						check = false;
						el.closest("Control-group").addClass("important");
					}else{
						el.closest("Control-group").removeClass("important");
					}
				}
				if( check ){
					warning.fadeOut()
					toggleOverlayClass("sending");
				}else warning.fadeIn();
				return check;
			},
			error: function(){
				toggleOverlayClass("error");
			},
			success: function(res){
				toggleOverlayClass(res.state);
			}
		})
	});
})