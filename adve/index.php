<?php  session_start();
$_SESSION['push_table']='adve';
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
<title>Ogłoszenia - Info</title>
<?php
echo "<meta name='viewport' content='target-densitydpi=device-dpi, initial-scale=1.0, user-scalable=no' />";
?>
<link rel="shortcut icon" href="../images/favicon.png" type="image/vnd.microsoft.icon" />
<link rel='stylesheet' type='text/css' href='../styles/general.css'>
<link rel='stylesheet' type='text/css' href='../styles/buttons.css'>
<link href='http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type='text/css' href="../jquery-ui/jquery-ui.css">
<link rel='stylesheet' type='text/css' href='../styles/main.css'>
<link rel='stylesheet' type='text/css' href='../styles/adve.css'>
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
var idgrupy="";

//pierwsze czynności
$(document).ready(function() {
       		
			
			refreshRoutine();
			$.get("imieniny.php",function(data){
				imieniny=data;
			});
			

			//czynności jeśli wersja mobilna
			if(mobile==true)
			{
				
			}
			//showAddContent("advert");
			$('#newAdveContent').keyup(function(){
				var charsLeft=1000-($('#newAdveContent').val().length);
				$('#charsLeftTextarea').html(charsLeft);
			});
			
       });
	   
$(window).resize(function(){
		if(mobile==false)
			{
				middleingInteractionWindow();
			}
});
var second=1;
setInterval(function(){
	if(second>10)
	{
		if($('#doUpdate').is(':checked'))
		{
			refreshRoutine();
		}
		second=1;
	}
	else
	{
		second++;
	}
},1000);
			
//pozostałe funkcje
function showAddContent()
{	
	$('div#addContent').fadeIn();
	middleingInteractionWindow();
}
function hideInteractionWindow()
{
	$('div#addContent').fadeOut();
}
function getAdves()
{
	$.post('getAdves.php',{id:id},function(data){
		$('div#advesThemselves').html(data);
	});
}
function listAdves()
{
	$.post('listAdve.php',{id:id},function(data){
		$('div#yourAdvesList').html(data);
	});
}
function middleingInteractionWindow()
{
	if(mobile==false)
	{
		var dowpisania = ($('div#addContent').height()-$('div#interactionWindow').height())/2;
		$('div#interactionWindow').css('margin-top',dowpisania+'px');
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
function addAdve()
{
	var title=$('#newAdveTitle').val();
	var content=$('#newAdveContent').val();
	var scopetype=$('#newAdveScopeType').val();
	var scopesArray=$('input:checkbox:checked.chosenScopes').map(function(){
				return this.id.substr(5);
			}).get();
	var scopes="";
	for(var j=0;j<scopesArray.length;j++)
	{
		scopes+=scopesArray[j]+",";
	}
	scopes=scopes.substring(0,scopes.length-1);
	var isOn=$('#newAdveSwitch').attr('state');
	if(title==''||content==''||scopetype==''||scopes=='')
	{
		showDialog('Błąd','Wypełnij wszystkie pola formularza',true);
	}
	else
	{
	$.post('addAdve.php',{title:title,content:content,scopetype:scopetype,scopes:scopes,ison:isOn},function(data){
		if(data=='done')
		{
			showDialog("Wykonano","Dodano ogłoszenie",true);
			refreshRoutine();
			hideInteractionWindow();
			//updateTableSQL('adve');
		}
		else
			showDialog("Błąd!",data,true);
	});
	}
}
function refreshRoutine()
{
	getAdves();
	listAdves();
}
function toggleAdve(id)
{
	if($('img#adveSwitch'+id).attr('state')==1)
	{
		//znaczy ukrywanie ogłoszenia
		$('img#adveSwitch'+id).attr('src','../images/adve/Switch Off-100.png');
		$('img#adveSwitch'+id).attr('state',0);
		$.post('toggleAdve.php',{adveId:id,adveState:0},function(data){
			refreshRoutine();
			//updateTableSQL('adve');
		});
	}
	else
	{
		//znaczy pokazywanie ogłoszenia
		$('img#adveSwitch'+id).attr('src','../images/adve/Switch On-100.png');
		$('img#adveSwitch'+id).attr('state',1);
		$.post('toggleAdve.php',{adveId:id,adveState:1},function(data){
			refreshRoutine();
			//updateTableSQL('adve');
		});
	}
}
function toggleNewAdveSwitch()
{
	if($('img#newAdveSwitch').attr('state')==1)
	{
		//znaczy ukrywanie ogłoszenia
		$('img#newAdveSwitch').attr('src','../images/adve/Switch Off-100.png');
		$('img#newAdveSwitch').attr('state',0);
	}
	else
	{
		//znaczy pokazywanie ogłoszenia
		$('img#newAdveSwitch').attr('src','../images/adve/Switch On-100.png');
		$('img#newAdveSwitch').attr('state',1);
	}
}
function showHideLists()
{
	if($('#newAdveScopeType').val()=='klasowe')
	{
		$('#newAdveChooseGroupsList').hide();
		$('#newAdveChooseClassesList').show();
	}
	else
	{
		$('#newAdveChooseClassesList').hide();
		$('#newAdveChooseGroupsList').show();
	}
}
function showAdveActions()
{
	$('span.adveActions').toggle();
}
function removeAdve(adveId)
{
	$.post('removeAdve.php',{id:adveId},function(data){
		if(data=='done')
		{
			showDialog('Sukces','Pomyślnie usunięto ogłoszenie',true);
			refreshRoutine();
			hideInteractionWindow();
			//updateTableSQL('adve');
		}
		else
			showDialog('Błąd!',data,true);
	});
}
</script>
<body>
<div id='totalcontainer'>
<div id="upcontainer">
<div id="upbelt">
<img id='platformUpperLogo' src='../images/logo-info.png'>
<div id='otherFunctionsDiv'>
<div class='otherFunction' onclick="switchSite('common')">
	<div><img class='otherFunctionIcon' src='../images/icons/Share-100.png'></div>
	<div>Forum</div>
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
<?php
if($_SESSION['userclass']=='nauczyciel'||$_SESSION['userclass']=='dyrektor'||$_SESSION['userclass']=='administrator')
echo "<div id='leftBelt'>
<div id='addAdveButton' onclick='showAddContent()'><img src='../images/icons/Add List-100.png' style='width:30px; height:30px; margin-right:5px' class='inlinowe-mid'><span class='inlinowe-mid'>dodaj ogłoszenie</span></div>
<div id='yourAdves'>
<h4 style='float:right'><button class='ultraSmallButon' onclick='showAdveActions()'>edytuj</button></h4>
<h4>Twoje ogłoszenia</h4>
<div id='yourAdvesList'>
<div class='yourAdve' uid='sdfte45td'>
<span>test<img class='adveSwitch' state=1 id='adveSwitchsdfte45td' onclick=\"toggleAdve('sdfte45td')\" src='../images/adve/Switch On-100.png'></span>
</div>
</div>
</div>
</div>";
?>
<div id='advesThemselves'>
</div>
<p class="copyrightFooter">Copyright &copy; by Piotr Muzyczuk<br/>Ikony z serwisu Icons8 (icons8.com)</p>
</div>

</div>

<div id="addContent">
<div id="interactionWindow">
<img onclick='hideInteractionWindow()' class='closingXInHeader' src='../images/closingx.png'><div id='addAdvertHeader'>Dodaj ogłoszenie</div>
<div id='addAdvertContent'>
<p>Tytuł: <input type='text' id='newAdveTitle'></p>
<p>Zawartość: <br/><textarea id='newAdveContent' maxlength=1000></textarea><br/>pozostało znaków: <span id='charsLeftTextarea'>1000</span></p>
<p>Typ: <select id='newAdveScopeType' onchange="showHideLists()">
<option value='klasowe'>klasowe</option>
<option value='grupowe'>grupowe</option>
</select>
</p>
<div id='newAdveChooseClassesList'>
Zaznacz klasy, do których kierujesz ogłoszenie:<br/>
<?php
$query_019="SELECT * FROM users ORDER BY CHAR_LENGTH(user_class),user_class ASC";
$result_019=mysqli_query($con,$query_019);
$takenClass=array();
while($row_019=mysqli_fetch_array($result_019))
{
	if(!in_array($row_019['user_class'],$takenClass))
	{
		echo "<input type='checkbox' class='chosenScopes' id='class".$row_019['user_class']."'><label for='class".$row_019['user_class']."'>".$row_019['user_class']."</label><br/>";
		$takenClass[]=$row_019['user_class'];
	}
}

?>
</div>
<div id='newAdveChooseGroupsList'>
Zaznacz grupy, do których kierujesz ogłoszenie:<br/>
<?php
$query_019="SELECT * FROM groups ORDER BY name ASC";
$result_019=mysqli_query($con,$query_019);
$takenClass=array();
while($row_019=mysqli_fetch_array($result_019))
{
	if(!in_array($row_019['uid'],$takenClass))
	{
		echo "<input type='checkbox' class='chosenScopes' id='group".$row_019['uid']."'><label for='group".$row_019['uid']."'>".$row_019['name']."</label><br/>";
		$takenClass[]=$row_019['uid'];
	}
}

?>
</div>
<p id='newAdveChooseClasses'></p>
<p><span class='inlinowe-mid' style='margin-right:10px'>Pokazuj:</span><img class='inlinowe-mid' id='newAdveSwitch' state=1 onclick="toggleNewAdveSwitch()" src='../images/adve/Switch On-100.png'></p>
<div class='interactionWindowButtons'><button class='confirmButton mediumButton centeredButton' onclick="addAdve()">Gotowe</button>
<button class='cancelButton mediumButton centeredButton' onclick="$('div#addContent').fadeOut();">Anuluj</button></div>
</div>
</div>
</div>

<div id='dialogSpace'></div>
<div id='doUpdateDiv'><input type='checkbox' id='doUpdate' checked onchange="$('#refreshButton').toggle()"><label for='doUpdate'>autoodświeżanie co 10 sekund</label><button class='smallButton' id='refreshButton' style='display:none' onclick='refreshRoutine()'>odśwież</button></div>

</body>
</html>
