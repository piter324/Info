function showDialog(title,content,useX,type)
{
	$('#dialogWindowTitle').html(title);
	$('#dialogWindowContent').html(content);
	
	if(useX==false)
	{
		$('#dialogClosingX').hide();
	}
	
	$('#dialogWindow').css('margin-top',($(window).height()-$('#dialogWindow').height())/2);
	$('#dialogWindow').slideDown();
	$('#dialogWindowContainer').slideDown();
}
function closeDialog()
{
	$('#dialogWindow').slideUp();
	$('#dialogWindowContainer').slideUp();
}