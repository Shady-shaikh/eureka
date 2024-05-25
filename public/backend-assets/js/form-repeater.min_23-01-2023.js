$(document).ready(function(){$(".file-repeater, .contact-repeater, .repeater-default").repeater({
	show:function(){
	$(this).slideDown()
	inittypeahead();
},hide:function(e){
	confirm("Are you sure you want to delete this element?")&&$(this).slideUp(e)
setTimeout(function(){ calculate_grand_total(); }, 500);
	inittypeahead();	
}
})});