
$("#display-button").click(function(){
	$("#mobile-list").toggleClass("hidden");

	if( $("#mobile-list").hasClass("hidden")){
		$("#display-button").html('<p>&#9660;</p>');
	} else {
		$("#display-button").html('<p>&#9650;</p>');
	}
	
});


