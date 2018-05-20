<?php session_start(); 
if($_SESSION['userid']=='')
{
	header('Location:../index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<?php
//echo "<meta name='viewport' content='target-densitydpi=device-dpi, initial-scale=1.0, user-scalable=no' />";
?>
<link rel="shortcut icon" href="images/favicon.ico" type="image/vnd.microsoft.icon" />
<link rel='stylesheet' type='text/css' href='../styles/biblioteka.css'>
<title>Biblioteka Collegium Salesianum</title>
<script src='../jquery.js'></script>
<script>
//constanty potrzebne do działania
const uzytkownik = "<?php echo $_SESSION['username'];?>";
const id = "<?php echo $_SESSION['userid'];?>";
const klasa = "<?php echo $_SESSION['userclass'];?>";
var mobile=false;
$(document).ready(function()
       {
			/*if(navigator.userAgent.match(/Android/i)
				|| navigator.userAgent.match(/webOS/i)
				|| navigator.userAgent.match(/iPhone/i)
				|| navigator.userAgent.match(/iPad/i)
				|| navigator.userAgent.match(/iPod/i)
				|| navigator.userAgent.match(/BlackBerry/i)
				|| navigator.userAgent.match(/Windows Phone/i))
				{
					mobile=true;
					$('link[href="../styles/biblioteka.css"]').attr('href','../styles/biblioteka-mobile.css');
				}
				else
				{
					mobile=false;
				}*/
			if(klasa!="nauczyciel"&&klasa!="dyrektor"&&klasa!="administrator")
			{
				
			}
			listCatalogue();
			listBorrowed();
			listCast();
       });

function listCatalogue()
{
	var filtrowanakolumna=$('#fkolumna').val();
	var filtr=$('#ftekst').val();
	
	$.post("listcatalogue.php",{fk:filtrowanakolumna,f:filtr},function(data){
		$('div#catalogueList').html(data);
	});
}
function listBorrowed()
{
	$.post("listborrowed.php",{uid:id},function(data){
		if(data!="")
		{
			$('div#borrowed').html("<p class='sectionHeader'>Pozycje nieoddane:</p>"+data);
		}
		else
		{
			$('div#borrowed').html("<p class='sectionHeader'>Pozycje nieoddane:</p><p class='notext'>(brak nieoddanych pozycji)</p>");
		}
	});
}
function listCast()
{
	$.post("listcast.php",{uid:id},function(data){
		if(data!="")
		{
			$('div#cast').html("<p class='sectionHeader'>Pozycje oddane:</p>"+data);
		}
		else
		{
			$('div#cast').html("<p class='sectionHeader'>Pozycje oddane:</p><p class='notext'>(brak oddanych pozycji)</p>");
		}
	});
}
</script>
</head>
<body>
<div id='totalcontainer'>
<div id='upcontainer'>
<div id='upbelt'>
<img class='logo' src='../images/CS_logo.png'><span class='nazwa'><img src='../images/logo-info-biblioteka.png' style='height:200px; width:440px; vertical-align:middle'></span>
</div>
<div id='underupbelt'>
<p style='margin-top:5px; margin-bottom:2px'>Witaj <b><?php echo $_SESSION['username'];?></b>!</p><p style='margin-top:5px; font-size:16px'>Poniżej możesz zobaczyć swoją kartę biblioteczną oraz przeszukać zasoby biblioteki.</p>
</div>
</div>
<div id='container'>
<div id='libCard'>
<p class='header'>Moja karta (<?php echo $_SESSION['username'];?>)</p>
<div id='borrowed'>
<p class='sectionHeader'>Pozycje nieoddane:</p>
<p><span class='inlinowe'><span style='font-weight:bold'>Książka "Być jak Steve Jobs" Leander Kahney</span><br />wydawnictwo Znak; ISBN: <a href='http://i-ksiazka.pl/view_book_list.php?fraza_isb=978-83-240-1670-9' target='_blank'>978-83-240-1670-9</a> </span><span class='inlinowe'></span></p>
</div>
<div id='cast'>
</div>
</div>
<div id='searching'>
<p class='header'>Wyszukiwanie</p>
<div id='mediaTypeList'>
</div>
<div id='textSearching'>
<p>Filtruj po: 
<select id='fkolumna'>
<option value='tytul'>tytule</option>
<option value='autor'>autorze</option>
<option value='slowo_kluczowe'>słowie kluczowym</option>
</select>
<input type='text' id='ftekst' onkeyup="listCatalogue()">
<button onclick='listCatalogue()' style='margin-left:30px'>odśwież manualnie</button>
</p>
</div>
<div id='catalogueList'>
</div>
</div>
<div id='doinfosalez' onclick="window.location='../main.php'">
<img src='../images/logo-info.png' style='width:100px; height:45px'>
</div>
</body>
</html>