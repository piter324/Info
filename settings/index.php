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
<title>Ustawienia - Info</title>
<?php
echo "<meta name='viewport' content='target-densitydpi=device-dpi, initial-scale=1.0, user-scalable=no' />";
?>
<link rel="shortcut icon" href="../images/favicon.png" type="image/vnd.microsoft.icon" />
<link rel='stylesheet' type='text/css' href='../styles/general.css'>
<link rel='stylesheet' type='text/css' href='../styles/buttons.css'>
<link href='http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type='text/css' href="../jquery-ui/jquery-ui.css">
<link rel='stylesheet' type='text/css' href='../styles/main.css'>
<link rel='stylesheet' type='text/css' href='../styles/settings.css'>
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
       		
			$.get("imieniny.php",function(data){
				imieniny=data;
			});
			
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
function showAddContent(type,scopetype,groupid)
{
	
		$.post("../interactionwindowfill.php",{d:newdzien,m:newmiesiac,y:newrok,t:type,c:klasa,st:scopetype,uid:id,gid:groupid},function(data){
			$('div.interactionwindow').html(data);
			if(mobile==false)
			{	$('div.interactionwindow').css('overflow','hidden');	}
			if(type=="changepass"&&mobile==false)
			{
				$('div.interactionwindow').height(220);
			}
			else if(type=="changeprofilepicture"&&mobile==false)
			{
				$('div.interactionwindow').height(280);
			}
			else if(type=="changemail"&&mobile==false)
			{
				$('div.interactionwindow').height(220);
			}
			else if(type=="showprofile"&&mobile==false)
			{
				$('div.interactionwindow').height(700);
			}
			else if(type=="advert"&&mobile==false)
			{
				$('div.interactionwindow').height(($(window).height()*0.75));
				$('div.interactionwindow').css('overflow','auto');
			}
			else if(type=="managegroups"||type=="createOrEditGroup")
			{
				if(mobile==false)
				{	$('div.interactionwindow').height(540);	}
				if(scopetype=="edit")
				{
					idgrupy=groupid;
				}
				if(klasa!="nauczyciel"&&klasa!="dyrektor"&&klasa!="administrator")
				{
					$('span.groupAction').hide();
					$('div.groupName').css('width','460px');
					$('div.groupName').css('text-align','center');
				}
			}
			
			$('div.addcontent').fadeIn();
			middleingInteractionWindow();
			
		});
		
}
function zmienhaslo()
{
		var old_pass = $('#oldpass').val();
		var new_pass = $('#newpass').val();
	if(new_pass!='')
	{
	$.post("changepass.php",{i:id,o:old_pass,n:new_pass},function(data){
		if (data=="done")
		{
			showDialog("Sukces","Hasło zmieniono pomyślnie",true);
			$('div.addcontent').fadeOut();
		}
		else {	showDialog("Błąd!",data,true);	}
	});
	}
	else
	{	showDialog("Błąd!","Hasło nie może być puste!",true);	}
}
function zmienmaila()
{
	var new_mail = $('#newmail').val();
	if(new_mail!="")
	{
	
	$.post("changemail.php",{uid:id,n:new_mail},function(data){
		if (data=="done")
		{
			showDialog("Sukces","Adres e-mail zmieniono pomyślnie",true);
			$('div.addcontent').fadeOut();
		}
		else {	showDialog("Błąd!",data,true);	}
	});
	}
	else
	{	showDialog("Błąd!","Adres e-mail nie może być pusty!",true);	}
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
//grupy
function createGroup(alerts,groupid)
{
	var nazwa = $('#nazwagrupy').val();
	var zakres = "";
	var zakresarray=$('input:checkbox:checked.scopes').map(function(){
				return this.id.substr(6);
			}).get();
	for(var i=0;i<zakresarray.length;i++)
		{
			zakres=zakres+zakresarray[i];
			if(i+1<zakresarray.length)
			{zakres=zakres+",";}
		}
	$.post("creategroup.php",{uid:id,members:zakres,name:nazwa,gid:groupid},function(data){
		if(data=="done"&&alerts==true)
		{
			showAddContent("managegroups");
		
			if(alerts==true)
			showDialog("Sukces","Grupę dodano pomyślnie",true);
		}
	});
	
}
function deleteGroup(groupid,alerts)
{
	$.post("deletegroup.php",{id:groupid},function(data){
	if(data=="done")
	{
		showAddContent("managegroups");
		
		if(alerts==true)
		showDialog("Sukces","Grupę usunięto pomyślnie",true);
	}
	});
}
function editGroup()
{
	deleteGroup(idgrupy,false);
	createGroup(false,idgrupy);
	showDialog("Sukces","Grupę zmodyfikowano pomyślnie",true);
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
<div class='otherFunction' onclick="switchSite('adve')">
	<div><img class='otherFunctionIcon' src='../images/icons/adv-100.png'></div>
	<div>Ogłoszenia</div>
</div>
<div class='otherFunction' onclick="window.location.href='../main.php'">
	<div><img class='otherFunctionIcon' src='../images/icons/Calendar 7-100.png'></div>
	<div>Terminarz</div>
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
<div class="container">
<h1><?php echo $_SESSION['username'];?></h1>
<h2>Informacje</h2>
<img src='<?php echo $profilowe;?>' id='profiloweShowProfile'>
<div id='profileRoundup'>
<h10>Email:</h10>
<div class='marginedBottom'><?php echo $_SESSION['email'];?></div>
<h10>Twoja klasa to:</h10>
<div class='marginedBottom'><?php echo $_SESSION['userclass'];?></div>
<h10>Należysz do grup:</h10>
<div class='marginedBottom'>
<?php
	$counter=0;
	$query="SELECT name FROM groups INNER JOIN group_members ON groups.uid=group_members.groupuid WHERE group_members.memberid=".$_SESSION['userid'];
	$result=mysqli_query($con,$query);
	echo "<ul>";
	while($row=mysqli_fetch_array($result))
	{
		$counter++;
		echo "<li>".$row['name']."</li>";
	}
	echo "</ul>";
	if($counter==0)
		echo "<em>brak</em>";
?>
</div>
</div>

<h2>Ustawienia</h2>
<div id='settingsActions'>
<div class='settingsAction' onclick="showAddContent('changepass');"><div><img src='../images/icons/Password Filled-100.png'></div>Zmień hasło</div>
<div class='settingsAction' style='display:none' onclick="showAddContent('changeprofilepicture');"><div><img src='../images/icons/SLR Back Side Filled-100.png'></div>Zmień zdjęcie profilowe</div>
<div class='settingsAction' onclick="showAddContent('changemail');"><div><img src='../images/icons/Email-100.png'></div>Zmień swój adres e-mail</div>
<div class='settingsAction' onclick="showAddContent('managegroups');"><div><img src='../images/icons/User Group Filled-100-black.png'></div>
<?php 
if($_SESSION['userclass']=='nauczyciel'||$_SESSION['userclass']=='dyrektor'||$_SESSION['userclass']=='administrator')
{
	echo "Zarządzaj grupami";
}
else
{
	echo "Pokaż grupy";
}
?></div>
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
