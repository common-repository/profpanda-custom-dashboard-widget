(function ($) {

	if ($('#pcdw_field_dash_position').val()=="core"){
		$('.dpriority').show();			
	} else {
		$('.dpriority').hide();	
	}
	

	$('#pcdw_field_dash_position').click(function(){
		if ($('#pcdw_field_dash_position').val()=="core"){
			$('.dpriority').show();			
		} else{
			$('.dpriority').hide();			
		}	
	});	

})(jQuery);