<html>
<head>
<meta charset="UTF-8">
</head>
<script src='../../jquery.js'></script>
<script>
var userclass = '<?php echo $_COOKIE[userclass];?>'
if (userclass!="dyrektor"&&userclass!="administrator")
{
	alert("Nie masz uprawnień do pracy z tą aplikacją");
	window.location="http://info.salez.edu.pl";
}
function initializeCrypting()
{
	var rowod = $('#od').val();
	var rowdo = $('#do').val();
	if($('#toDatabase').is(':checked'))
	{var todatabase = 1;}
	else
	{var todatabase = 0;}
	$.post('crypting.php',{pocz:rowod,koniec:rowdo,dobazy:todatabase},function(data){
	$('#output').html(data);
	});
}
</script>
<body>
<p>Od (włącznie): <input type="text" id="od"></p>
<p>Do (włącznie): <input type="text" id="do"></p>
<p><input type="checkbox" id="toDatabase">Zapisz w bazie danych</p>
<button onclick="initializeCrypting()">Koduj!</button>
<div id='output'>
</div>
</body>
</html>