<?php  session_start();
if($_SESSION['userid']=='')
{
	header('Location:index.php');
}
$config_array = parse_ini_file("../config_info.ini");
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
<title>Terminarz - Info</title>
<?php
echo "<meta name='viewport' content='target-densitydpi=device-dpi, initial-scale=1.0, user-scalable=no' />";
?>
<link rel="shortcut icon" href="images/favicon.png" type="image/vnd.microsoft.icon" />
<link rel='stylesheet' type='text/css' href='styles/general.css'>
<link rel='stylesheet' type='text/css' href='styles/buttons.css'>
<link href='http://fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type='text/css' href="jquery-ui/jquery-ui.css">
<link rel="stylesheet" type='text/css' href="styles/jPushMenu.css">
<link rel='stylesheet' type='text/css' href='styles/main.css'>
<link rel='stylesheet' href='dialogAPI/dialogBox.css'>
<script src='jquery.js'></script>
<script src='jquery-ui/jquery-ui.js'></script>
<script src='jPushMenu.js'></script>
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

var idwydarzenia = 0;
var check=false;
var addedevent="";

var zaznaczonepersony="";

var idgrupy="";

var pokazany=false;

var idadresata=0;
var scrollbarpoautoruszeniu=0;
var highestmessageid=0;
var dodajemyfavoritsy=false;

var imieniny="";

var lastupdusers=["0","0","0"];
var lastupdevents=["0","0","0"];
var lastupdreplacements=["0","0","0"];
var lastmessageininboxid=0;
var odczytaneinbox='N';

var destination='';

//pierwsze czynności
$(document).ready(function() {
       		

			if(klasa!="nauczyciel"&&klasa!="dyrektor"&&klasa!="administrator")
			{
				$('#addIndivEventButton').hide();
			}
			
			$('div.addcontent').hide();
			$('div.eventActionDiv').hide();
			$.get("imieniny.php",function(data){
				imieniny=data;
			});

			//alert(dzien+" / "+miesiac+" / "+rok);

			refreshRoutine();
			
       });
setTimeout(function(){
	refreshRoutine();
},500);
	   
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
function refreshRoutine()
{
	showCalendar();
	showDayDetails(newdzien,newmiesiac,newrok);
}
function hideInteractionWindow()
{
	$('div.addcontent').fadeOut();
}
function resizeMessengerBeltUserList()
{
	var show = false;
	if(show!=false)
	{
		var wysmax = $(window).height();
		wysmax = wysmax - $('div.menuBeltSection').height() - 50;
		//alert(wysmax);
		$('div.messengerBelt').css('height',wysmax+'px');
		$('div.messengerBeltUserList').css('max-height',(wysmax-150)+'px');
	}
	
}
function middleingInteractionWindow()
{
	if(mobile==false)
	{
		var dowpisania = ($('div.addcontent').height()-$('div.interactionwindow').height())/2;
		$('div.interactionwindow').css('margin-top',dowpisania+'px');
	}
}
function showCalendar()
{
			$.post('calendar.php',{y:newrok, m:newmiesiac, d:newdzien, c:klasa, u:uzytkownik, uid:id},function(data){
					$('div.calendar').html(data);
                   });
}
function wyloguj()
{
	$.get('logOut.php',function(data){
		if(data==1)
		{
			window.location='index.php';
		}
	});
}
function showAddContent(type,scopetype,groupid)
{
	
		$.post("interactionwindowfill.php",{d:newdzien,m:newmiesiac,y:newrok,t:type,c:klasa,i:idwydarzenia,st:scopetype,uid:id,gid:groupid},function(data){
			$('div.interactionwindow').html(data);
			if(mobile==false)
			{	$('div.interactionwindow').css('overflow','hidden');	}
			if(type=="changepass"&&mobile==false)
			{
				$('div.interactionwindow').height(280);
			}
			else if(type=="changeprofilepicture"&&mobile==false)
			{
				$('div.interactionwindow').height(280);
			}
			else if(type=="changemail"&&mobile==false)
			{
				$('div.interactionwindow').height(280);
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
				{	$('div.interactionwindow').height(500);	}
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
			else if(type=="createevent"||type=="modifyevent")
			{
				if(mobile==false)
				{	$('div.interactionwindow').height(600);	}
				$('#datepicker').datepicker({
				showOn: "button",
				buttonImage: "images/calendar.gif",
				buttonImageOnly: true,
				buttonText: "Wybierz datę"
				});
			}
			else if(type=="createormodifyreplacement")
			{
				if(mobile==false)
				{	$('div.interactionwindow').height(550);	}
				var ludzie = [<?php

				$query="SELECT id,username FROM users WHERE user_class='nauczyciel' OR user_class='dyrektor' OR user_class='administrator' ORDER BY user_class DESC";
				$result=mysqli_query($con,$query);
				while($row=mysqli_fetch_array($result))
				{
					$output.="{label: '".$row[username]."',value: '".$row[username]."',backval: '".$row[id]."'},";
				}
				echo substr($output,0,-1);
				?>];
				
				$('#replacedText').autocomplete({
				minLength: 1,
				source: ludzie
				});
				
				$('#replacingText').autocomplete({
				minLength: 1,
				source: ludzie
				});
			}
			
			$('div.addcontent').fadeIn();
			middleingInteractionWindow();
			
		});
		
}
function addReplacement(actiontype)
{
	var replaced = $('input#replacedText').val();
	if($('input#noLesson').is(':checked'))
	{
		var replacing = 'klasa/grupa zwolniona';
	}
	else
	{
		var replacing = $('input#replacingText').val();
	}
	if($('input#replacementForClass').is(':checked'))
	{
		var scope = $('select#replacementScopeClass').val();
		var scopetype = 'klasowe';
	}
	else
	{
		var scope = $('select#replacementScopeGroup').val();
		var scopetype = 'grupowe';
	}
	var lessonnumber = $('input#lessonNumber').val();
	var roomnumber = $('input#roomNumber').val();
	var subject = $('input#replacementSubject').val();
	
	var miesiac='';
	if(newmiesiac<10){miesiac='0'+newmiesiac;}
	else{miesiac=newmiesiac;}
	var date = newrok+'-'+miesiac+'-'+newdzien;
	
	if(replacing!=''&&replaced!=''&&lessonnumber!=''&&roomnumber!=''&&subject!='')
	{
	$.post("addreplacement.php",{rped:replaced,rping:replacing,sc:scope,sct:scopetype,lsn:lessonnumber,rm:roomnumber,sbj:subject,dt:date},function(data){
		if(data=='done')
		{
			if(actiontype=='addition')
			{
				showDialog('Pytanie','Czy chcesz dodać kolejne zastępstwo?',false,"Tak&showReplacements();					showAddContent('createormodifyreplacement');$('div#dialogSpace').hide();*Nie&showReplacements();$('div.addcontent').hide();$('div#dialogSpace').hide();");
			}
			else if(actiontype=='modification')
			{
				showDialog('Sukces','Zastępstwo zmodyfikowano pomyślnie!',true);
				showReplacements();
				$('div.addcontent').hide();

			}
		}
		else
		{
			showDialog('Błąd',data,true);
		}
	});
	}
	else
	{
		showDialog('Błąd','Wypełnij wszystkie pola',true);
	}
	
}
function showDayDetails(day,month,year)
{
	//alert(day+"-"+month+"-"+year);
	$('td').removeClass('selectedDay');
	$('td#'+day).addClass('selectedDay');
	newrok=year;
	newmiesiac=month;
	newdzien=day;
	writeDayDetails();
	showReplacements();
	$('div.eventActionDiv').hide();
	
}
function writeDayDetails()
{
	filtrowanaKlasa = "";
	$.post("selecteddayintro.php",{d:newdzien,m:newmiesiac,y:newrok,c:klasa},function(data){
		$('div.selectedDayIntro').html(data);
		});
		
	//filtrowanie klas
	if(klasa=="nauczyciel"||klasa=="dyrektor"||klasa=="administrator")
	{
		var aktvalueclass=$('#searchfieldclass').val();	
		$.post("classlist.php",{day:newdzien,month:newmiesiac,year:newrok,nowvalue:aktvalueclass},function(data){
				$('div.filteringclass').html(data);
		});
		filtrowanaKlasa=aktvalueclass;
	}
	
	//filtrowanie grup
	var aktvaluegroup=$('#searchfieldgroup').val();	
	$.post("grouplist.php",{day:newdzien,month:newmiesiac,year:newrok,nowvalue:aktvaluegroup,uid:id,c:klasa},function(data){
			$('div.filteringgroup').html(data);
		});
		
	filtrowanaGrupa=aktvaluegroup;
	
	//alert($('#searchfield').val());
	$.post("eventlistclass.php",{d:newdzien,m:newmiesiac,y:newrok,c:klasa,uid:id,f:filtrowanaKlasa},function(data){
	if(data!="")
	{	
		$('div.eventlistClass').html(data);
	}
	else
	{ 
		$('div.eventlistClass').html("<p class='noEventInfo'>brak wydarzeń klasowych</p>");
	}
	});
	
	$.post("eventlistgroup.php",{d:newdzien,m:newmiesiac,y:newrok,c:klasa,uid:id,f:filtrowanaGrupa},function(data){
	if(data!="")
	{	
		$('div.eventlistGroup').html(data);
	}
	else
	{ 
		$('div.eventlistGroup').html("<p class='noEventInfo'>brak wydarzeń grupowych</p>");
	}
	});
	
	$.post("eventlistindividual.php",{d:newdzien,m:newmiesiac,y:newrok,c:klasa,uid:id,u:uzytkownik},function(data){
	if(data!="")
	{
		$('div.eventlistIndiv').html(data);
	}
	else
	{ 
		$('div.eventlistIndiv').html("<p class='noEventInfo'>brak wydarzeń indywidualnych</p>");
	}
	
	
	});
}
function showReplacements()
{
	var rodzajFiltra="";
	var tekstFiltra="";
	
	//alert($('#searchfield').val());
	$.post("listreplacements.php",{d:newdzien,m:newmiesiac,y:newrok,c:klasa,uid:id,ft:rodzajFiltra,ft:tekstFiltra},function(data){
	if(data!="")
	{	var tresc = "<div id='tableHeader'><div class='lessonNumber'>Nr lekcji</div><div class='roomNumber'>Nr sali</div><div class='replaced'>Zastępowany/a</div><div class='replacing'>Zastępujący/a</div><div class='replacementScope'>Objęta klasa/grupa</div><div class='replacementSubject'>Przedmiot</div>";
	if(klasa=='dyrektor'||klasa=='administrator')
		{
			tresc+="<div class='replacementEditDelete'>Akcje</div>";
		}
		tresc+="</div>"+data;
		$('div#replacementsList').html(tresc);
	}
	else
	{ 
		$('div#replacementsList').html("<p class='noReplacementsInfo'>brak zastępstw na wybrany dzień</p>");
	}
	});
}
function deleteReplacement(replacementid,alerts) 
{
	$.post("deletereplacement.php",{rid:replacementid},function (data) 
	{
		if(data=='done'&&alerts==true)
		{
			showDialog('Sukces','Zastępstwo usunięto pomyślnie',true);
			showReplacements();
		}
	});
}
function modifyReplacement(replacementid)
{
	deleteReplacement(replacementid,false);
	addReplacement('modification');
	
}
function calendarControl(miesczyrok,plusczyminus)
{
	if(miesczyrok=="miesiac")
	{
		newmiesiac=parseInt(newmiesiac)+plusczyminus;
		if(newmiesiac<1) {newrok=parseInt(newrok)-1; newmiesiac=12;}
		else if(newmiesiac>12) {newrok=parseInt(newrok)+1; newmiesiac=1;}
		//alert(newmiesiac);
		showCalendar();		
	}
	else if	(miesczyrok=="rok")
	{
	
		newrok=parseInt(newrok)+plusczyminus;
		//alert(newrok);
		showCalendar();
	}
}
function addEvent(alerts,typ)
{
	var tytul = $('#text').val();
	var szczegoly = $('#eventdetails').val();
	var przedmiot = $('#subject').val();
	/*var dzien = $('#dateday').val();
	var miesiac = $('#datemonth').val();
	var rok = $('#dateyear').val();*/
	var dataa = $('#datepicker').val();
	var datasplitted = dataa.split('.');
	var zakres="";
	
	if(tytul.indexOf("</")>-1||tytul.indexOf("input")>-1||tytul.indexOf("javascript")>-1||tytul.indexOf("$.")>-1||tytul.indexOf("$(")>-1||tytul.indexOf("button")>-1)
	{
		showDialog("Błąd!","Szczegóły wydarzenia zawierają kod elementów aktywnych, co jest niedozwolone. Usuń niedozwolony kod i kliknij Gotowe.",true);
	}
	else if(szczegoly.indexOf("</")>-1||szczegoly.indexOf("input")>-1||szczegoly.indexOf("javascript")>-1||szczegoly.indexOf("$.")>-1||szczegoly.indexOf("$(")>-1||szczegoly.indexOf("button")>-1)
	{
		showDialog("Błąd!","Tytuł wydarzenia zawiera kod elementów aktywnych, co jest niedozwolone. Usuń niedozwolony kod i kliknij Gotowe.",true);
	}
	else if(przedmiot.indexOf("</")>-1||przedmiot.indexOf("input")>-1||przedmiot.indexOf("javascript")>-1||przedmiot.indexOf("$.")>-1||przedmiot.indexOf("$(")>-1||przedmiot.indexOf("button")>-1)
	{
		showDialog("Błąd!","Przedmiot wydarzenia zawiera kod elementów aktywnych, co jest niedozwolone. Usuń niedozwolony kod i kliknij Gotowe.",true);
	}
	else 
	{
	if(klasa=="nauczyciel"||klasa=="dyrektor"||klasa=="administrator")
	{
		if(typ=="klasowe"||typ=="grupowe")
		{
			var zakresarray=$('input:checkbox:checked.scopes').map(function(){
				return this.value;
			}).get();
		}
		else if(typ=="indywidualne")
		{
			var zakresarray=$('input:checkbox:checked.scopes').map(function(){
				return this.id.substr(6);
			}).get();
		}
		
		for(var i=0;i<zakresarray.length;i++)
		{
			zakres=zakres+zakresarray[i];
			if(i+1<zakresarray.length)
			{zakres=zakres+",";}
		}
		
		}
		else
		{
			if(typ=="klasowe")
			zakres=klasa;
			else if (typ=="grupowe")
			{
				var zakresarray=$('input:checkbox:checked.scopes').map(function(){
					return this.value;
				}).get();
				
				for(var i=0;i<zakresarray.length;i++)
				{
					zakres=zakres+zakresarray[i];
					if(i+1<zakresarray.length)
					{zakres=zakres+",";}
				}
			}
		}
		
		//alert(zakres);
		//alert(tytul+"-"+szczegoly+"-"+przedmiot+"-"+zakres+"-"+dzien+"-"+miesiac+"-"+rok);
		$.post("addevent.php",{text:tytul,details:szczegoly,subject:przedmiot,type:typ,day:datasplitted[0],month:datasplitted[1],year:datasplitted[2],scope:zakres,author_id:id},function(data){
			if(data=="done"&&alerts!=false)
			{
				showDialog('Sukces','Wydarzenie dodano pomyślnie. Czy chcesz dodać kolejne?',false,"Tak&showCalendar();showDayDetails(newdzien,newmiesiac,newrok);$('div#dialogSpace').hide();*Nie&$('div.addcontent').fadeOut();showCalendar();showDayDetails(newdzien,newmiesiac,newrok);$('div#dialogSpace').hide();");
					
			}
			else if (alerts!=false) { showDialog("Błąd!",data,true);}
			
			
		});
		zaznaczonepersony="";
		zakres="";
		}
}
function displayEventActionDiv(iddiv)
{
		idwydarzenia=iddiv.substr(5);
		var div = $('#'+iddiv);
		var position = div.position();
		var posx = position.left;
		var posy = position.top;
		var width = div.width();
		var height = div.height();
		$('div.eventActionDiv').css('left',posx);
		$('div.eventActionDiv').css('top',posy);
		$('div.eventActionDiv').width(width);
		$('div.eventActionDiv').height(height-10);
		$('div.eventActionDiv').show();
		var margintop = (height - $('div.eventActionDivElement').height())/2;
		$('div.eventActionDivElement').css('margin-top',margintop);
	
}
function deleteEvent(alerts)
{
	$.post("deleteevent.php",{i:idwydarzenia},function(data){
		if(data=="done")
		{
			if(alerts!=false)
			{ showDialog("Sukces!","Wydarzenie usunięto pomyślnie",true); }
			showCalendar();
			showDayDetails(newdzien,newmiesiac,newrok);
		}
	});
}
function modifyEvent(typ)
{
		var dataa = $('#datepicker').val();
		var datasplitted = dataa.split('.');
		$.post("checkdate.php",{day:datasplitted[0],month:datasplitted[1],year:datasplitted[2]},function(data){
			if(data=="passed")
			{
				deleteEvent(false);
				addEvent(false,typ);
				showDialog("Sukces!","Wydarzenie zmodyfikowano pomyślnie",true);
				$('div.addcontent').fadeOut();
			}
			else
			{
				showDialog("Błąd!",data,true);
			}
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
function showWelcomeUsernameAction()
{
	var spanposition = $('span.welcomeUsername').position();
	var spanheight = $('span.welcomeUsername').height();
	var spanwidth = $('span.welcomeUsername').width();
	$('div.welcomeUsernameDropdown').css('left',spanposition.left-10);
	$('div.welcomeUsernameDropdown').css('top',spanposition.top+spanheight+10);
	$('div.welcomeUsernameDropdown').css('width',spanwidth+10);
	$('div.welcomeUsernameDropdown').slideDown();
	clearTimeout($('span.welcomeUsername').data('timeoutId'));
}
function hideWelcomeUsernameAction()
{
	var someElementspan = $('span.welcomeUsername'),
					timeoutIdspan = setTimeout(function(){
						$('div.welcomeUsernameDropdown').slideUp();
						//alert('chowanie');
					}, 500);
				//set the timeoutId, allowing us to clear this trigger if the mouse comes back over
				someElementspan.data('timeoutId', timeoutIdspan);
}


//messenger
function middleingConversationWindowContainer()
{
	var dowpisania2 = ($('div.messagingCanvas').height()-675)/2;
	$('div.conversationWindowContainer').css('margin-top',dowpisania2+'px');
}
function fillmessengerBeltUserList()
{
	var filtr = $('#messengerFilterTB').val();

	$.post("fillavailableusers.php",{c:klasa,filter:filtr,uid:id},function(data){
		if(data!='') { $('div.messengerBeltUserListAvailable').html(data); }
		else { $('div.messengerBeltUserListAvailable').html("<p style='text-align:center; font-style:italic'>brak dostępnych</p>"); }
	});
	$.post("fillunavailableusers.php",{c:klasa,filter:filtr,uid:id},function(data){
		if(data!='') { $('div.messengerBeltUserListUnavailable').html(data); }
		else { $('div.messengerBeltUserListUnavailable').html("<p style='text-align:center; font-style:italic'>brak niedostępnych</p>"); }
	});
}
function fillmessengerBeltFavorites()
{
	$.post("fillfavorites.php",{uid:id},function(data){
		if(data!='') { $('div.messengerBeltFavorites').html(data); }
		else { $('div.messengerBeltFavorites').html("<p style='text-align:center; font-style:italic'>brak ulubionych</p>"); }
	});
}
function fillMessagesList()
{
	$.post("fillmessageslist.php",{uid:id},function(data){
		if(data!='') { $('div.messagesList').html(data); }
		else { $('div.messagesList').html("<p style='text-align:center; font-style:italic'>brak wiadomości</p>"); }
	});
}
function addToFavorites(iddelikwenta)
{
	$.post("addtofavorites.php",{uid:id,idtoadd:iddelikwenta},function(data){
		if(data=='done')
		{
			fillmessengerBeltUserList();
			fillmessengerBeltFavorites();
		}
	});
}
function removeFromFavorites(iddelikwenta)
{
	$.post("removefromfavorites.php",{uid:id,idtoremove:iddelikwenta},function(data){
		if(data=='done')
		{
			fillmessengerBeltUserList();
			fillmessengerBeltFavorites();
		}
	});
}
function showMessengerBelt()
{
	fillmessengerBeltUserList();
	fillmessengerBeltFavorites();
	fillMessagesList();
	resizeMessengerBeltUserList();
	//$('div.messagingCanvas').show();
	
	
}
function showHideMessengerBeltSection(sectionname)
{
	if($('div.'+sectionname).is(':visible'))
	{
		$('div.'+sectionname).slideUp();
		$('div#'+sectionname+'HeaderArrow').html("<img src='images/downarrow.png'>");
	}
	else
	{
		$('div.'+sectionname).slideDown();
		$('div#'+sectionname+'HeaderArrow').html("<img src='images/uparrow.png'>");
	}
}
function startConversation(idrecipienta)
{
	if(!$('div.messagingCanvas').is(':visible'))
	{
		$('div.messagingCanvas').fadeIn('fast');
	}
	if(mobile==true)
	{
		$('.jPushMenuBtn,body,.cbp-spmenu').removeClass('disabled active cbp-spmenu-open cbp-spmenu-push-toleft cbp-spmenu-push-toright');
	}
	idadresata = idrecipienta;
	var convtype="";
	$.post("fillconversationwindow.php",{sid:id,rid:idrecipienta,ctype:convtype},function(data){
		highestmessageid=0;
		$('div.conversationWindowContainer').html(data);
		if(mobile==false)
		{	middleingConversationWindowContainer();	}
		refreshMessageList();
		$('div.conversationWindowContainer').show();
		$('div.listedMessages').animate({
				scrollTop: $('div.listedMessages').get(0).scrollHeight
			}, 100, function(){
					scrollbarpoautoruszeniu=$('div.listedMessages').scrollTop();
				});
		
	});
}
function sendMessage(idrecipienta)
{
	var tresc = $('textarea.newMessageText').val();
	
	var convtype="";
	$.post("sendmessage.php",{sid:id,rid:idrecipienta,ctype:convtype,text:tresc},function(data){
		if(data=="done")
		{
			
			refreshMessageList();
			$('div.listedMessages').animate({
				scrollTop: $('div.listedMessages').get(0).scrollHeight
			}, 100, function(){
					scrollbarpoautoruszeniu=$('div.listedMessages').scrollTop();
				});
			
			$('textarea.newMessageText').val("");
		}
		else if(data=="")
		{
			if(confirm("Nie udało się wysłać wiadomości. Połączenie z serwerem mogło zostać przerwane. Czy chcesz ponowić próbę?"))
			{
				sendMessage(idrecipienta);
			}
			else
			{
				$('textarea.newMessageText').val("");
			}
		}
		else
		{
			$('textarea.newMessageText').val("");
			showDialog("Błąd!",data,true);
		}
	});
}
function refreshMessageList()
{

	$.post("refreshmessagelist.php",{sid:id,rid:idadresata,hmid:highestmessageid},function(data){
		$('div.listedMessages').append(data);
		
		var showedSentMessagesArray=$('div.sentMessage').map(function(){
				return this.id;
			}).get();
		var showedReceivedMessagesArray=$('div.receivedMessage').map(function(){
				return this.id;
			}).get();
		var showedMessagesArray=showedSentMessagesArray.concat(showedReceivedMessagesArray);
		//alert(showedMessagesArray.join(","));
		for(var j=0;j<showedMessagesArray.length;j++)
		{
			if(parseInt(showedMessagesArray[j])>parseInt(highestmessageid))
			{
				highestmessageid=showedMessagesArray[j];
			}
		}
				
		//scrollowanie
		if($('div.listedMessages').scrollTop()==scrollbarpoautoruszeniu)
		{
			$('div.listedMessages').animate({
				scrollTop: $('div.listedMessages').get(0).scrollHeight
			}, 100, function(){
					scrollbarpoautoruszeniu=$('div.listedMessages').scrollTop();
				});
		}
		
		//alert(highestmessageid);
		showedMessagesArray.length = 0;
	});
	
	var currentlyUnreadMessagesArray=$('span.unreadMessageText').map(function(){
				return this.id;
			}).get();
	var currentlyUnreadMessages = currentlyUnreadMessagesArray.join(',');
	$.post('checkifread.php',{cunrmess:currentlyUnreadMessages},function(data){
		var outputUnread = data.split(',');
		for(i=0;i<currentlyUnreadMessagesArray.length;++i)
		{
		//alert(currentlyUnreadMessagesArray[i]);
			if(outputUnread.indexOf(currentlyUnreadMessagesArray[i].substr(3))<=-1)
			{
				$('#'+currentlyUnreadMessagesArray[i]).attr('class','readMessageText');
			}
		}
	});
	
}
function setToRead()
{
	$.post("settoread.php",{sid:id,rid:idadresata},function(data){
		if(data!='')
		{
			var iddozmiany=data.split(",");
			for(var i=0;i<iddozmiany.length;i++)
			{
				$('#txt'+iddozmiany[i]).attr('class','readMessageText');
			}
		}
	});
}
function showMinuses()
{
	if($('span.deletingFromFavorites').is(':visible'))
	{
		$('span.deletingFromFavorites').hide();
	}
	else
	{
		$('span.deletingFromFavorites').show();
	}
}
function switchSite(dest)
{
	window.location.href=dest+"/index.php";
}
</script>
<body>
<div id='totalcontainer'>
<div id="upcontainer">
<div id="upbelt">
<img id='platformUpperLogo' src='images/logo-info.png'>
<div id='otherFunctionsDiv'>
<div class='otherFunction' onclick="switchSite('common')">
	<div><img class='otherFunctionIcon' src='images/icons/Share-100.png'></div>
	<div>Forum</div>
</div>
<div class='otherFunction' onclick="switchSite('adve')">
	<div><img class='otherFunctionIcon' src='images/icons/adv-100.png'></div>
	<div>Ogłoszenia</div>
</div>
<div class='otherFunction' onclick="switchSite('settings')">
	<div><img class='otherFunctionIcon' src='images/icons/Settings 3-100.png'></div>
	<div>Ustawienia</div>
</div>
<?php if($_SESSION['userclass']=='administrator')
echo "<div class='otherFunction' onclick=\"switchSite('adminpanel')\">
	<div><img class='otherFunctionIcon' src='images/icons/Administrative Tools-100.png'></div>
	<div>Administrowanie</div>
</div>";
?>
</div>
</div>
<div id="underupbelt">
<?php
if(file_exists("images/profiles/".$_SESSION['userid'].".jpg"))
{
	$profilowe = "images/profiles/".$_SESSION['userid'].".jpg?dt=".date("His");
}
else
{
	$profilowe = "images/profiles/defaultprofile.png";
}
echo "<div class='inlinowe'><img class='profiloweSmall' id='profiloweUpBelt' src='".$profilowe."' style=''></div><div class='inlinowe'><span class='welcomeUsername'> Witaj <b>".$_SESSION['username']."</b>!</span><span id='dataimieniny' onmouseover=\"changeDateToNames('do')\"  onmouseout=\"changeDateToNames('undo')\">Dziś jest ";
echo date('d.m.Y');
echo "</span><button class='logOutButton mediumButton' onclick='wyloguj();'>Wyloguj!</button></div>";
?>

</div>

</div>
<div class="container">

<div class="calendarplusdetails">
<div class="calendar">
kalendarz
</div>

<div class="eventListCanvas">
<div id="eventList">
<div class="selectedDayIntro">
</div>

<div id="eventlistClassContainer">
<div id='eventlistClassHeader'>
<p class='eventListContainerHeaderText'>wydarzenia klasowe</p>
<div class="filteringclass">
</div>
</div>
<div class="eventlistClass">
</div>
</div>

<div id="eventlistGroupContainer">
<div id='eventlistGroupHeader'>
<p class='eventListContainerHeaderText'>wydarzenia grupowe</p>
<div class="filteringgroup">
</div>
</div>
<div class="eventlistGroup">
</div>
</div>

<div id="eventlistIndivContainer">
<div id='eventlistIndivHeader'>
<p class='eventListContainerHeaderText'>wydarzenia indywidualne</p>
</div>
<div class="eventlistIndiv">
</div>
</div>

</div>
</div>
</div>

<div class="akcje">
<p><button class='bigButton' id='addClassEventButton' onclick="showAddContent('createevent','klasowe');">Dodaj wydarzenie klasowe</button>

<button class='bigButton' id='addGroupEventButton' onclick="showAddContent('createevent','grupowe');">Dodaj wydarzenie grupowe</button>

<button class='bigButton' id='addIndivEventButton' onclick="showAddContent('createevent','indywidualne');">Dodaj wydarzenie indywidualne</button></p>
</div>
<?php
echo"<div class=\"akcjemob\">
<p><button class='addEvent addClassEventButton' onclick=\"showAddContent('createevent','klasowe');\">Dodaj wydarzenie klasowe</button></p>

<p><button class='addEvent addGroupEventButton' onclick=\"showAddContent('createevent','grupowe');\">Dodaj wydarzenie grupowe</button></p>

<p><button class='addEvent addIndivEventButton' onclick=\"showAddContent('createevent','indywidualne');\">Dodaj wydarzenie indywidualne</button></p>
</div>";
?>

<div class='replacements'>
<div id='replacementsTitle'>Zastępstwa</div>
<div id='replacementsContent'>
<div id='filteringReplacements'>Filtrowanie</div>
<div id='replacementsList'>
<div id='tableHeader'>
<div class='lessonNumber'>Nr lekcji</div>
<div class='roomNumber'>Nr sali</div>
<div class='replaced'>Zastępowana</div>
<div class='replacing'>Zastępująca</div>
<div class='scope'>Objęta klasa/grupa</div>
<div class='subject'>Przedmiot</div>
<div class='replacementEditDelete'>Akcje</div>
</div>

<div class='replacementItself'>
<div class='lessonNumber'>1</div>
<div class='replaced'><div class='inlinowe leftOne'><img class='profiloweSmall circularPhoto' src='images/profiles/defaultprofile.png'></div><div class='inlinowe'>Szklarska Jolanta</div></div>
<div class='replacing'><div class='inlinowe leftOne'><img class='profiloweSmall circularPhoto' src='images/profiles/defaultprofile.png'></div><div class='inlinowe'>Piętka-Baumgart Joanna</div></div>
<div class='replacementEditDelete'><div class='replacementAction' onclick='showAddContent("createormodifyreplacement","1");'><img class='editdeleteSmall' src='images/edit-black.png'> edytuj</div><div class='replacementAction' onclick='deleteReplacement("1");'><img class='editdeleteSmall' src='images/delete-black.png'> usuń</div></div>
</div>

</div>
</div>

</div>
<?php
if($_SESSION['userclass']=='dyrektor'||$_SESSION['userclass']=='administrator')
{
	echo "<div id='akcjaZast'>
	<button class='bigButton' id='addReplacement' onclick=\"showAddContent('createormodifyreplacement');\">Dodaj zastępstwo</button>
	</div>";
}
?>

<p class="copyrightFooter">Copyright &copy; by Piotr Muzyczuk<br/>Ikony z serwisu Icons8 (icons8.com)</p>
</div>

</div>

<div class="addcontent">
<div class="interactionwindow">
<button onclick="$('div.addcontent').hide();">ukryj</button>
</div>
</div>
<div class="eventActionDiv" onclick="$('div.eventActionDiv').hide();">
<div class="eventActionDivElement" onclick="showAddContent('modifyevent','undefined');">
<img style='margin-bottom:0px;' src='images/edit.png'>
<p class="eventActionDivDescription">edytuj</p>
</div>
<div class='eventActionDivElement' onclick="deleteEvent();">
<img style='margin-bottom:0px;' src='images/delete.png'>
<p class="eventActionDivDescription">usuń</p>
</div>
</div>

<nav class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right">
		<h3></h3>

<div class="messengerBelt">
<p class="messengerBeltHeader"><img src='images/infomesslogo.png' style='height:60px;'><span style='margin-right:70px;'>info.messenger</span></p>
<p style='font-style:italic; text-align:center;'>Kliknij na użytkownika, z którym chcesz rozpocząć rozmowę.</p>
<div class="messengerBeltUserList">

<div class='messengerBeltSectionHeader'><div class='inlinowe' style='width:250px'>Skrzynka odbiorcza:</div><div class='inlinowe' id='inboxHeaderArrow' onclick="showHideMessengerBeltSection('inbox')"><img src='images/uparrow.png'></div></div>
<div class='inbox'>
<div class='messagesList'>

<div class='messageFromRecipient'>
<div class='recipientPhoto'><img src='images/profiles/defaultprofile.png' height=50 width=50></div><div class='recipientInfo'><p class='recipientInfoHeader' style='margin-bottom:5px'>Ksawery Iksiński</p><p class='recipientInfoLastMessage' style='margin-top:0px'>Hello!</p></div>
</div>

</div>
</div>

<p style='text-align:center'>Filtruj: <input type='text' id='messengerFilterTB' onkeyup='fillmessengerBeltUserList()'><button onclick="$('#messengerFilterTB').val(''); fillmessengerBeltUserList();">X</button></p>
<div class='messengerBeltSectionHeader'><div class='inlinowe' style='width:250px'>Ulubione osoby: <button id='pokazminusy' onclick='showMinuses()'>edytuj</button></div><div class='inlinowe' id='messengerBeltFavoritesHeaderArrow' onclick="showHideMessengerBeltSection('messengerBeltFavorites')"><img src='images/uparrow.png'></div></div>
<div class="messengerBeltFavorites">
<p style='text-align:center; font-style:italic'>brak ulubionych</p>
<p class="messengerBeltUser" style='display:none'>Ulubiony Wincent</p>
</div>
<div class='messengerBeltSectionHeader'><div class='inlinowe' style='width:250px'>Dostępni:</div><div class='inlinowe' id='messengerBeltUserListAvailableHeaderArrow' onclick="showHideMessengerBeltSection('messengerBeltUserListAvailable')"><img src='images/uparrow.png'></div></div>
<div class="messengerBeltUserListAvailable">
<p class="messengerBeltUser">Dostępny Jerzy</p>
</div>
<div class='messengerBeltSectionHeader'><div class='inlinowe' style='width:250px'>Niedostępni:</div><div class='inlinowe' id='messengerBeltUserListUnavailableHeaderArrow' onclick="showHideMessengerBeltSection('messengerBeltUserListUnavailable')"><img src='images/uparrow.png'></div></div>
<div class="messengerBeltUserListUnavailable">
<p class="messengerBeltUser">Niedostępny Jerzy</p>
</div>
</div>
</div>

</nav>

<div class="messagingCanvas">
<div class='conversationWindowContainer'>
</div>
</div>
<div id='dialogSpace'></div>
<div id='doUpdateDiv'><input type='checkbox' id='doUpdate' checked onchange="$('#refreshButton').toggle()"><label for='doUpdate'>autoodświeżanie co 10 sekund</label><button class='smallButton' id='refreshButton' style='display:none' onclick='refreshRoutine()'><img src='images/icons/Refresh-100.png' id='refreshButtonIcon' class='inlinowe-mid'><span class='inlinowe-mid'>odśwież</span></button></div>

</body>
</html>
