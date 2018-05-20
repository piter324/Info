function showDialog(title,content,showClosingX,buttons)
{
	//buttons write like so: name1&function1#name2&function2...
	$.post('dialogAPI/dialogboxShow.php',{title:title,content:content,showx:showClosingX,buttons:buttons},function(data){
		$('#dialogSpace').show();
		$('#dialogSpace').html(data);
		$('#dialogBox').css('top',($(window).height()/2)-$('#dialogBox').height()/2);
		$('#dialogBox').css('left',($(window).width()/2)-($('#dialogBox').width()/2));
		$('#dialogBox').slideDown();
	});
}
function closeDialog() 
{
	$('#dialogBox').slideUp();
	$('#dialogSpace').hide();
}