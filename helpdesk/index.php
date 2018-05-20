<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<link rel="shortcut icon" href="images/favicon.ico" type="image/vnd.microsoft.icon" />
<link rel="stylesheet" type="text/css" href="styles/index.css">
<title>salezIT HELPDESK</title>
<script src='jquery.js'></script>
<script>
$(document).ready(function(){
	changeCategory('start');
});

$(window).scroll(function(){
	if($(window).scrollTop()>=380)
	{
		$("div#menubelt").addClass('fixedMenu');
	}
	else
	{
		$("div#menubelt").removeClass('fixedMenu');
	}
});
function changeCategory(cname)
{
	$("div#contentContainer").load(cname+".txt?time=<?php echo date('His');?>");
	$("div.menubeltMember").removeClass('clicked');
	$("div#"+cname).addClass('clicked');
}
</script>
</head>
<body>
<div id='titlebar'>
<img id='helpdeskLogo' src='images/salezIThelpdesk.png'>
</div>
<div id='menubelt'>
<div class='menubeltMember' id='start' onclick="changeCategory('start')">START</div>
<div class='menubeltMember highlightedMember' id='nowosci' onclick="changeCategory('nowosci')">NOWOŚCI</div>
<div class='menubeltMember' id='logowanie' onclick="changeCategory('logowanie')">Logowanie</div>
<div class='menubeltMember' id='wifi' onclick="changeCategory('wifi')">WiFi</div>
<div class='menubeltMember' id='dyskisieciowe' onclick="changeCategory('dyskisieciowe')">Dyski sieciowe</div>
<div class='menubeltMember' id='drukowanie' onclick="changeCategory('drukowanie')">Drukowanie</div>
<div class='menubeltMember' id='infosalez' onclick="changeCategory('infosalez')">INFO.salez</div>
<div class='menubeltMember' id='kontakt' onclick="changeCategory('kontakt')">Kontakt z pomocą techniczną</div>
</div>
<div id='contentContainer'>

<div class='contentMember'>
<div class='memberMetadata'>
<div class='articleNumber'>#1</div>
<div class='otherMetadata'>11.10.2014</div>
</div>
<div class='memberContents'>
<div class='memberTitle'>Drukowanie bezpośrednio w akwarium - <a href='Przewodnik.pdf'>pobierz PDF</a></div>
<div class='memberText'>
<object class='articlePDF' data='Przewodnik.pdf' type='application/pdf'>
Niestety twoja przeglądarka nie jest w stanie wyświetlić plików PDF. Co za szkoda :(
</object>
</div>
</div>
</div>

</div>
</body>
</html>
