$(function(){
	$(".deleteChosen").click(function(){
		var btn = $(this),
			el  = btn.closest(".tab-pane"),
			selector = jQuery(".group-checkable", el).data("set"),
			checked  = new Array;

		App.blockUI(el);
		
		jQuery(selector+":checked").each(function(){
			checked.push(jQuery(this).val());
		});

		if (!btn.data("set")){
			alert("Не установлен URL в data-set");
			return false;
		}
		jQuery.ajax({
			url: btn.data("set")+checked+"/", 
			dataType: 'json',
			complete: function(){
				App.unblockUI(el);
			},
			success: function(data){
				//console.log(data);
				if (data.state=="success"){
					location.reload(true);
				}
			}
		});
		return false;
	});
})