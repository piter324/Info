<?php  session_start();
if($_SESSION['userid']=='')
{
	header('Location:../index.php');
}
$config_array = parse_ini_file("../../config_info.ini");
$con = mysqli_connect($config_array['address'],$config_array['username'],$config_array['password'],$config_array['db_name']);
if(!$con)
{
	die("Connection to MySQL Database failed");
}
$con->set_charset("utf8");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<title>Forum - Info</title>
<?php
echo "<meta name='viewport' content='target-densitydpi=device-dpi, initial-scale=1.0, user-scalable=no' />";
?>
<link rel="shortcut icon" href="../images/favicon.png" type="image/vnd.microsoft.icon" />
<link rel='stylesheet' type='text/css' href='../styles/general.css'>
<link rel='stylesheet' type='text/css' href='../styles/buttons.css'>
<link href='http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type='text/css' href="../jquery-ui/jquery-ui.css">
<link rel='stylesheet' type='text/css' href='../styles/main.css'>
<link rel='stylesheet' type='text/css' href='../styles/common.css'>
<link rel='stylesheet' href='dialogAPI/dialogBox.css'>
<script src='../jquery.js'></script>
<script src='../jquery-ui/jquery-ui.js'></script>
<script src='dialogAPI/showDialog.js'></script>
<script>
//constanty potrzebne do działania
const uzytkownik = "<?php echo $_SESSION['username'];?>";
const id = "<?php echo $_SESSION['userid'];?>";
const klasa = "<?php echo $_SESSION['userclass'];?>";
const dzien = "<?php if(date('d')<10){echo ltrim(date('d'),'0');}else{echo date('d');}?>";
const miesiac = "<?php if(date('m')<10){echo ltrim(date('m'),'0');}else{echo date('m');}?>";//<- usuwanie 0 sprzed miesięcy <10
const rok = "<?php echo date('Y');?>";
var wysokoscdokumentu = 0;
var mobile = false;

//zmienne pomocnicze
var newrok = rok;
var newmiesiac = miesiac;
var newdzien = dzien;
var cnt=2;
var currScope="";

//pierwsze czynności
$(document).ready(function() {
       		
			
			//$('.toggle-menu').jPushMenu();
			$.get("imieniny.php",function(data){
				imieniny=data;
			});
			

			//czynności jeśli wersja mobilna
			if(mobile==true)
			{
				
			}
			//showAddContent("advert");
			
       });
	   
$(window).resize(function(){
		if(mobile==false)
			{
				middleingInteractionWindow();
			}
});
//pozostałe funkcje
function hideInteractionWindow()
{
	$('div.addcontent').fadeOut();
}
function middleingInteractionWindow()
{
	if(mobile==false)
	{
		var dowpisania = ($('div.addcontent').height()-$('div.interactionwindow').height())/2;
		$('div.interactionwindow').css('margin-top',dowpisania+'px');
	}
}
function wyloguj()
{
	$.get('../logOut.php',function(data){
		if(data==1)
		{
			window.location='../index.php';
		}
	});
}
function showEntries()
{
	$.post('showEntries.php',{scope:currScope},function(data){
			$('#timeline').html(data);
			if($('#refreshEntriesButton').is(':hidden'))
			{
				$('#refreshEntriesButton').show();
			}
	});
}
function reloadEntry(properid)
{
	$.post('showEntries.php',{upd:1,scope:properid},function(data){
			$('#entry'+properid).html(data);
	});
}
function reloadComments(properid)
{
	$.post('reloadComments.php',{uid:properid},function(data){
			$('#commentsList'+properid).html(data);
	});
}
function filterPersons()
{
	$('div.scopesdiv').hide();
	var filtr = $('#peopleFilterTB').val();
	$.post("filterpersons.php",{filter:filtr},function(data){
		var widoczneosoby = data.split(",");
		
		for(var i=0;i<widoczneosoby.length;i++)
		{
			$('#uczen'+widoczneosoby[i]).show();
		}
	});
	
}
function changeDateToNames(option)
{
	if(option=='do')
	{
		$('#dataimieniny').html(imieniny);
	}
	else if(option='undo')
	{
		var tekstdowpisania="<?php echo "Dziś jest "; echo date('d.m.Y');?>";
		$('#dataimieniny').html(tekstdowpisania);
	}
	
}
function switchSite(dest)
{
	window.location.href="../"+dest+"/index.php";
}
function changeScope()
{
	currScope=$('#scope').val();
	showEntries();
	$('#timelineContainer').show();
}
function showAddLinkToEntry()
{
	$('#newEntryLinkText').toggle();
}
function showAddLinkCommentTextbox(properid)
{
	$('#newCommentTextbox'+properid).toggle();
}
function clearFieldsEntry()
{
	$('#addEntryText').val('');
	$('#newEntryLinkText').val('');
}
function clearFieldsComment()
{
	$('.newCommentContent').val('');
	$('.newCommentLinkText').val('');
}
function addEntry()
{
	var tekst=$('#addEntryText').val();
	var link=$('#newEntryLinkText').val();
	if(tekst!='')
	{
	$.post('addEntry.php',{tekst:tekst,link:link,scope:currScope},function(data){
		if(data=='done')
		{
			showEntries();
			clearFieldsEntry();
		}
		else
			showDialog("Błąd!","Nie udało się dodać wpisu.",true);
	});
	}
	else
	{
		showDialog("Błąd!","Nie możesz dodać pustego wpisu",true);
	}
}
function showComments(properid)
{
	if($('#comments'+properid).is(':hidden'))
	{
		$('#comments'+properid).slideDown();
		$('#commentActions'+properid).html('ukryj komentarze');
	}
	else
	{
		$('#comments'+properid).slideUp();
		$('#commentActions'+properid).html('pokaż komentarze');
	}
}
function postComment(parentuid)
{
	var content=$('#newCommentContent'+parentuid).val();
	var link = $('#newCommentTextbox'+parentuid).val();
	if(content!='')
	{
	$.post('addComment.php',{tekst:content,link:link,scope:currScope,parentuid:parentuid},function(data){
		if(data=='done')
		{
			reloadComments(parentuid);
			updateCommentsCounter(parentuid);
			clearFieldsComment();
		}
		else
			showDialog("Błąd!","Nie udało się dodać komentarza",true);
	});
	}
	else
	{
		showDialog("Błąd!","Nie możesz dodać pustego komentarza",true);
	}
}
function changeMarks(properid,number,parentid)
{
	$.post('castVote.php',{uid:properid,number:number,parentid:parentid},function(data){
		if(data=='done')
		{
			updateMarksCounter(properid);
		}
	})
}
function deleteEntry(properid,isComment,parentuid)
{
	$.post('deleteEntry.php',{uid:properid},function(data){
		if(data=='done')
		{
			if(isComment)
			{
				reloadComments(parentuid);
				updateCommentsCounter(parentuid);
			}
			else
			{
				showEntries();
			}
		}
		else
			showDialog("Błąd!",data,true);
	});
}
function updateCommentsCounter(entryid)
{
	$.post('updateCommentsCounter.php',{uid:entryid},function(data){
		$('#commentCounter'+entryid).html(data);
	});
}
function updateMarksCounter(entryid)
{
	$.post('reloadMarks.php',{uid:entryid},function(data){
		$('#resultSpan'+entryid).html(data);
});

}
</script>
<body>
<div id='totalcontainer'>
<div id="upcontainer">
<div id="upbelt">
<img id='platformUpperLogo' src='../images/logo-info.png'>
<div id='otherFunctionsDiv'>
<div class='otherFunction' onclick="switchSite('adve')">
	<div><img class='otherFunctionIcon' src='../images/icons/adv-100.png'></div>
	<div>Ogłoszenia</div>
</div>
<div class='otherFunction' onclick="window.location.href='../main.php'">
	<div><img class='otherFunctionIcon' src='../images/icons/Calendar 7-100.png'></div>
	<div>Terminarz</div>
</div>
<div class='otherFunction' onclick="switchSite('settings')">
	<div><img class='otherFunctionIcon' src='../images/icons/Settings 3-100.png'></div>
	<div>Ustawienia</div>
</div>
<?php if($_SESSION['userclass']=='administrator')
echo "<div class='otherFunction' onclick=\"switchSite('adminpanel')\">
	<div><img class='otherFunctionIcon' src='../images/icons/Administrative Tools-100.png'></div>
	<div>Administrowanie</div>
</div>";
?>
</div>
</div>
<div id="underupbelt">
<?php
if(file_exists("../images/profiles/".$_SESSION['userid'].".jpg"))
{
	$profilowe = "../images/profiles/".$_SESSION['userid'].".jpg?dt=".date("His");
}
else
{
	$profilowe = "../images/profiles/defaultprofile.png";
}
echo "<div class='inlinowe'><img class='profiloweSmall' id='profiloweUpBelt' src='".$profilowe."' style='' onclick=\"showAddContent('showprofile')\"></div><div class='inlinowe'><span class='welcomeUsername'> Witaj <b>".$_SESSION['username']."</b>!</span><span id='dataimieniny' onmouseover=\"changeDateToNames('do')\"  onmouseout=\"changeDateToNames('undo')\">Dziś jest ";
echo date('d.m.Y');
echo "</span><button class='logOutButton mediumButton' onclick='wyloguj();'>Wyloguj!</button></div>";
?>

</div>

</div>
<div id="container">
<div id='pickScopeContainer'>
<button class='mediumButton' id='refreshEntriesButton' onclick='showEntries()'><img src='../images/icons/Refresh-100.png' id='refreshButtonIcon' class='inlinowe'><span class='inlinowe'>odśwież wpisy</span></button><p id='pickScope'>Wybierz klasę/grupę: 
<select id='scope' onchange="changeScope()">
<option selected disabled>wybierz</option>
<optgroup label='klasy'>	
<?php
if($_SESSION['userclass']=='nauczyciel'||$_SESSION['userclass']=='dyrektor'||$_SESSION['userclass']=='administrator')
{
$queryClass="SELECT * FROM users ORDER BY CHAR_LENGTH(user_class),user_class ASC";
$resultClass=mysqli_query($con,$queryClass);
$takenClasses=array();
while($rowClass=mysqli_fetch_array($resultClass))
{
	if(!in_array($rowClass['user_class'], $takenClasses))
	{
		echo "<option value='cl".$rowClass['user_class']."'>".$rowClass['user_class']."</option>";
		$takenClasses[]=$rowClass['user_class'];
	}
}
}
else
	echo "<option value='cl".$_SESSION['userclass']."'>".$_SESSION['userclass']."</option>";

?>
</optgroup>
<optgroup label='grupy'>
<?php
if($_SESSION['userclass']=='nauczyciel'||$_SESSION['userclass']=='dyrektor'||$_SESSION['userclass']=='administrator')
	$queryGroup="SELECT * FROM groups";
else
	$queryGroup="SELECT groups.* FROM groups INNER JOIN group_members ON groups.uid=group_members.groupuid WHERE group_members.memberid=".$_SESSION['userid'];

$resultGroup=mysqli_query($con,$queryGroup);
while($rowGroup=mysqli_fetch_array($resultGroup))
{
	echo "<option value='gr".$rowGroup['uid']."'>".$rowGroup['name']."</option>";
}
?>
</optgroup>
</select></p>
</div>

<div id='timelineContainer'>
<div id='addEntryContainer'>
<textarea id='addEntryText'></textarea>
<button class='mediumButton' id='addEntryButton' onclick='addEntry()'>Dodaj wpis</button><div id='addEntryLink'>
<button class='smallButton' id='addLinkToEntryButton' onclick='showAddLinkToEntry()'><img src='../images/icons/Add Link-100.png' class='actionIcon inlinowe-mid'><span class='inlinowe-mid'>dodaj link do wpisu</span></button><input type='text' id='newEntryLinkText' placeholder='tu wklej adres linku, który chcesz dodać do wpisu'></div>
<div id='addEntryActions'></div>
</div>
<div id='timeline'>
</div>
</div>
<p class="copyrightFooter">Copyright &copy; by Piotr Muzyczuk<br/>Ikony z serwisu Icons8 (icons8.com)</p>
</div>

</div>

<div class="addcontent">
<div class="interactionwindow">
<button onclick="$('div.addcontent').hide();">ukryj</button>
</div>
</div>

<div id='dialogSpace'></div>

</body>
</html>
