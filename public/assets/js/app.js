$(function(){

$(".slider.minutes").slider({
	value:0,
	min: 0,
	max: 60,
	step: 5,
	slide: function( event, ui ) {
		$("input.minutes" ).val( ui.value );
	}
});

$(".slider.hours").slider({
	value:0,
	min: 0,
	max: 12,
	slide: function( event, ui ) {
		$("input.hours" ).val( ui.value );
	}
});

$(".btn.delete").click( askIfOkToDelete );

});

function askIfOkToDelete(e)
{
	var want_to_delete = confirm("Are you sure you want to delete this value?");
	if(!want_to_delete){
		return e.preventDefault();
	}
}
